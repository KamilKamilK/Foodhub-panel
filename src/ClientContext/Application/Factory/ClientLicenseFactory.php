<?php declare(strict_types=1);

namespace App\ClientContext\Application\Factory;

use App\ClientContext\Application\Command\UpdateAddons\AddonUpdate;
use App\ClientContext\Application\Command\UpdateAddons\DeviceUpdate;
use App\ClientContext\Application\DTO\License\AddonProvision;
use App\ClientContext\Application\DTO\License\DeviceProvision;
use App\ClientContext\Application\DTO\License\LicenseProvisioningRequest;
use App\ClientContext\Domain\Entity\ClientAdditionalDevice;
use App\ClientContext\Domain\Entity\ClientAddon;
use App\ClientContext\Domain\Entity\ClientLicense;
use App\ClientContext\Domain\Repository\ClientLicenseRepositoryInterface;
use App\LicenseContext\Domain\Entity\License;
use App\LicenseContext\Domain\Entity\LicenseAdditionalDevice;
use App\LicenseContext\Domain\Entity\LicenseAddon;
use App\LicenseContext\Domain\Enum\PeriodEnum;
use App\ClientContext\Domain\Exception\ClientLicenseAddonNotFoundException;

use App\ClientContext\Domain\Exception\ClientLicenseDeviceUpdateException;

class ClientLicenseFactory
{
    public function __construct(
        private ClientLicenseRepositoryInterface $clientLicenseRepository,
        private int $yearPeriodLength,
        private int $monthPeriodLength,
    ) {
    }

    public function createLicense(LicenseProvisioningRequest $req): ClientLicense
    {
        $latestLicenseDate = $req->client->getLatestClientLicense()->getExpiredAt();
        $today = (new \DateTime())->setTime(0, 0, 0);

        if ($latestLicenseDate >= $today) {
            $validFrom = (clone $latestLicenseDate)->modify('+1 day')->setTime(0, 0, 0);
            $expiredAt = $req->period === PeriodEnum::YEAR->value
                ? (clone $latestLicenseDate)->modify('+' . $this->yearPeriodLength . ' days')
                : (clone $latestLicenseDate)->modify('+' . $this->monthPeriodLength . ' days');
        } else {
            $validFrom = $today;
            $expiredAt = $req->period === PeriodEnum::YEAR->value
                ? (clone $today)->modify('+' . $this->yearPeriodLength . ' days')
                : (clone $today)->modify('+' . $this->monthPeriodLength . ' days');
        }

        $clientLicense = ClientLicense::fromProvision(
            license:     $req->license,
            client:      $req->client,
            period:      $req->period,
            validFrom:   $validFrom,
            expiredAt:   $expiredAt,
            specialCode: $req->specialCode,
        );

        foreach ($req->addons as $addonProvision) {
            $clientLicense->addAddon(
                ClientAddon::forProvision($addonProvision->licenseAddon, $validFrom, $expiredAt)
            );
        }

        foreach ($req->devices as $deviceProvision) {
            for ($i = 0; $i < $deviceProvision->quantity; $i++) {
                $clientLicense->addAdditionalDevice(
                    ClientAdditionalDevice::forProvision($deviceProvision->device, $validFrom, $expiredAt)
                );
            }
        }

        return $clientLicense;
    }

    public function updateActiveLicenses(LicenseProvisioningRequest $req): void
    {
        $latestLicense = $req->client->getLatestClientLicense()->getLicense();
        $effectiveLicense = $req->license ?? $latestLicense;

        foreach ($req->client->getActiveClientLicenses() as $activeLicense) {
            if ($req->license) {
                $isMoreExpensive = $req->license->getPriceByPeriod($req->period)
                    >= $activeLicense->getLicense()->getPriceByPeriod($activeLicense->getPeriod());

                if ($isMoreExpensive && $req->license->getId() !== $activeLicense->getLicense()->getId()) {
                    $activeLicense->upgradeLicense($req->license);
                }
            }

            $this->applyAddonProvisions($req->addons, $activeLicense, $effectiveLicense);
            $this->applyDeviceProvisions($req->devices, $activeLicense, $effectiveLicense);
            $this->clientLicenseRepository->update($activeLicense);
        }
    }

    /** @param AddonUpdate[]  $addonUpdates
     *  @param DeviceUpdate[] $deviceUpdates */
    public function updateActiveAddons(
        array $addonUpdates,
        array $deviceUpdates,
        ClientLicense $clientLicense,
    ): ClientLicense {
        foreach ($addonUpdates as $addonUpdate) {
            /** @var ClientAddon|false $activeAddon */
            $activeAddon = $clientLicense->getAddons()->filter(
                fn(ClientAddon $a) =>
                    $a->getLicenseAddon()->getType() === $addonUpdate->addonType &&
                    $a->getLicenseAddon()->getCategory() === $addonUpdate->addonCategory
            )->first();

            if (!$activeAddon) {
                throw new ClientLicenseAddonNotFoundException();
            }

            $addonUpdate->isActiveOnNextPeriod
                ? $activeAddon->planForNextPeriod()
                : $activeAddon->cancelNextPeriod();
        }

        foreach ($deviceUpdates as $deviceUpdate) {
            $activeDevices = $clientLicense->getAdditionalDevices()->filter(
                fn(ClientAdditionalDevice $d) =>
                    $d->getLicenseAdditionalDevice()->getType() === $deviceUpdate->deviceType
            );

            if ($deviceUpdate->quantity > $activeDevices->count()) {
                throw new ClientLicenseDeviceUpdateException();
            }

            foreach ($activeDevices as $key => $activeDevice) {
                $key + 1 <= $deviceUpdate->quantity
                    ? $activeDevice->planForNextPeriod()
                    : $activeDevice->cancelNextPeriod();
            }
        }

        return $clientLicense;
    }

    /** @param AddonProvision[] $addons */
    private function applyAddonProvisions(array $addons, ClientLicense $activeLicense, License $newLicense): void
    {
        foreach ($activeLicense->getAddons() as $clientAddon) {
            $addonInNewLicense = $newLicense->getAddons()->filter(
                fn(LicenseAddon $la) =>
                    $la->getType() === $clientAddon->getLicenseAddon()->getType() &&
                    $la->getCategory() === $clientAddon->getLicenseAddon()->getCategory()
            )->first();

            if (
                $addonInNewLicense &&
                $addonInNewLicense->getPriceByPeriod($activeLicense->getPeriod()) >=
                $clientAddon->getLicenseAddon()->getPriceByPeriod($activeLicense->getPeriod()) &&
                $addonInNewLicense->getId() !== $clientAddon->getLicenseAddon()->getId()
            ) {
                $clientAddon->migrateToAddon($addonInNewLicense);
            }

            $inProvision = false;
            foreach ($addons as $addonProvision) {
                if (
                    $addonProvision->licenseAddon->getType() === $clientAddon->getLicenseAddon()->getType() &&
                    $addonProvision->licenseAddon->getCategory() === $clientAddon->getLicenseAddon()->getCategory()
                ) {
                    $inProvision = true;
                    break;
                }
            }

            $inProvision ? $clientAddon->planForNextPeriod() : $clientAddon->cancelNextPeriod();
        }

        foreach ($addons as $addonProvision) {
            $exists = $activeLicense->getAddons()->filter(
                fn(ClientAddon $a) =>
                    $a->getLicenseAddon()->getType() === $addonProvision->licenseAddon->getType() &&
                    $a->getLicenseAddon()->getCategory() === $addonProvision->licenseAddon->getCategory()
            )->first();

            if (!$exists) {
                $activeLicense->addAddon(
                    ClientAddon::forProvision(
                        $addonProvision->licenseAddon,
                        $activeLicense->getValidFrom(),
                        $activeLicense->getExpiredAt(),
                    )
                );
            }
        }
    }

    /** @param DeviceProvision[] $devices */
    private function applyDeviceProvisions(array $devices, ClientLicense $activeLicense, License $newLicense): void
    {
        foreach ($activeLicense->getAdditionalDevices() as $clientDevice) {
            $newDeviceEntity = $newLicense->getAdditionalDevices()->filter(
                fn(LicenseAdditionalDevice $item) =>
                    $item->getType() === $clientDevice->getLicenseAdditionalDevice()->getType()
            )->first();

            if (
                $newDeviceEntity &&
                $newDeviceEntity->getPriceByPeriod($activeLicense->getPeriod()) >=
                $clientDevice->getLicenseAdditionalDevice()->getPriceByPeriod($activeLicense->getPeriod()) &&
                $newDeviceEntity->getId() !== $clientDevice->getLicenseAdditionalDevice()->getId()
            ) {
                $clientDevice->migrateToDevice($newDeviceEntity);
            }
        }

        $existedCount = $activeLicense->getAdditionalDevices()->count();

        foreach ($devices as $deviceProvision) {
            $newCount = $deviceProvision->quantity - $existedCount;

            for ($i = 0; $i < $newCount; $i++) {
                $activeLicense->addAdditionalDevice(
                    ClientAdditionalDevice::forProvision(
                        $deviceProvision->device,
                        $activeLicense->getValidFrom(),
                        $activeLicense->getExpiredAt(),
                    )
                );
            }

            $activeDevices = $activeLicense->getAdditionalDevices()->filter(
                fn(ClientAdditionalDevice $d) =>
                    $d->getLicenseAdditionalDevice()->getType() === $deviceProvision->device->getType()
            );

            foreach ($activeDevices as $key => $activeDevice) {
                $key + 1 <= $deviceProvision->quantity
                    ? $activeDevice->planForNextPeriod()
                    : $activeDevice->cancelNextPeriod();
            }
        }
    }
}
