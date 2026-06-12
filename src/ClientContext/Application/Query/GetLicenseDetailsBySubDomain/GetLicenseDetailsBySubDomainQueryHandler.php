<?php declare(strict_types=1);

namespace App\ClientContext\Application\Query\GetLicenseDetailsBySubDomain;

use App\ClientContext\Application\DTO\LicenseDetails\AccountStatusDTO;
use App\ClientContext\Application\DTO\LicenseDetails\ClientAddonDTO;
use App\ClientContext\Application\DTO\LicenseDetails\ClientLicenseDTO;
use App\ClientContext\Domain\Repository\ClientRepositoryInterface;
use App\ClientContext\Domain\Entity\ClientAdditionalDevice;
use App\ClientContext\Domain\Entity\ClientAddon;
use App\ClientContext\Domain\Entity\ClientLicense;
use App\LicenseContext\Domain\Enum\AdditionalDeviceTypeEnum;
use App\ClientContext\Domain\Exception\ClientNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetLicenseDetailsBySubDomainQueryHandler
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {
    }

    public function __invoke(GetLicenseDetailsBySubDomainQuery $query): AccountStatusDTO
    {
        $locale    = $query->getLocale();
        $subDomain = $query->getSubDomain()->getValue();

        $client = $this->clientRepository->findOneBySubdomainForLicenseDetails($subDomain);

        if (!$client) {
            throw new ClientNotFoundException();
        }

        $isActive = $client->getLatestClientLicense()->isActive();
        $posLimit = $client->getCurrentClientLicense()->getLicense()->getIncludedPoses();
        $menuLimit = $client->getCurrentClientLicense()->getLicense()->getMenuLimit();
        $includedFoodHubOrder = $client->getCurrentClientLicense()->getLicense()->getIncludedFoodHubOrder();

        $additionalPoses = $client->getCurrentClientLicense()->getAdditionalDevices()->filter(
            function (ClientAdditionalDevice $additionalDevice) {
                return $additionalDevice->getExpiredAt()->format('Y-m-d') >= (new \DateTime())->format('Y-m-d')
                    && $additionalDevice->getLicenseAdditionalDevice()->getType() === AdditionalDeviceTypeEnum::POS->value;
            }
        );

        $activeAdditionalPoses = $additionalPoses->filter(
            fn (ClientAdditionalDevice $d) => $d->isPlannedForNextPeriod()
        );

        $currentLicenseDTO = new ClientLicenseDTO(
            $client->getCurrentClientLicense(),
            $client->getCurrentClientLicense()->getValidFrom(),
            $client->getCurrentClientLicense()->getExpiredAt(),
            $locale,
        );

        $activeLicenses = $client->getActiveClientLicenses()->map(
            fn (ClientLicense $clientLicense) => new ClientLicenseDTO(
                $clientLicense,
                $clientLicense->getValidFrom(),
                $clientLicense->getExpiredAt(),
                $locale,
            )
        )->toArray();

        $addons = $client->getCurrentClientLicense()->getAddons()->map(
            function (ClientAddon $addon) use ($client) {
                $validFrom  = $addon->getValidFrom();
                $expiredAt  = $this->findLatestAddonDate($addon, $client->getActiveClientLicenses()->toArray());

                return new ClientAddonDTO($validFrom, $expiredAt, $addon);
            }
        )->toArray();

        return new AccountStatusDTO(
            expirationDate:       $client->getLatestClientLicense()->getExpiredAt(),
            createdAt:            $client->getCreatedAt(),
            isActive:             $isActive,
            posLimit:             $posLimit + $additionalPoses->count(),
            additionalPoses:      $activeAdditionalPoses->count(),
            menuLimit:            $menuLimit,
            includedFoodHubOrder: $includedFoodHubOrder,
            addons:               $addons,
            currentLicense:       $currentLicenseDTO,
            activeLicenses:       $activeLicenses,
        );
    }

    private function findLatestAddonDate(ClientAddon $currentAddon, array $activeLicenses): \DateTime
    {
        $expiredAt = $currentAddon->getExpiredAt();

        /** @var ClientLicense $activeLicense */
        foreach ($activeLicenses as $activeLicense) {
            $existedAddon = $activeLicense->getAddons()->filter(
                fn (ClientAddon $clientAddon) =>
                    $clientAddon->getLicenseAddon()->getType() === $currentAddon->getLicenseAddon()->getType()
                    && $clientAddon->getLicenseAddon()->getCategory() === $currentAddon->getLicenseAddon()->getCategory()
            )->first();

            if ($existedAddon && $existedAddon->getExpiredAt() > $expiredAt) {
                $expiredAt = $existedAddon->getExpiredAt();
            }
        }

        return $expiredAt;
    }
}
