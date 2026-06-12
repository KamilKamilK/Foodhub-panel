<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Service;

use App\ClientContext\Domain\Entity\Client;
use App\ClientContext\Domain\Entity\ClientAdditionalDevice;
use App\ClientContext\Domain\Entity\ClientAddon;
use App\ClientContext\Domain\Entity\ClientLicense;
use App\OrderContext\Domain\Entity\ClientLicenseOrder;
use App\OrderContext\Domain\Entity\ClientLicenseOrderAddon;
use App\OrderContext\Domain\Entity\ClientLicenseOrderDevice;
use App\LicenseContext\Domain\Entity\License;
use App\LicenseContext\Domain\Entity\LicenseAdditionalDevice;
use App\LicenseContext\Domain\Entity\LicenseAddon;
use App\LicenseContext\Domain\Enum\PeriodEnum;
use App\OrderContext\Domain\Exception\LicenseDeviceNotAvailableException;
use App\OrderContext\Domain\ValueObject\AddonsPricingBreakdown;
use App\OrderContext\Domain\ValueObject\PricingBreakdown;
use App\Shared\Domain\ValueObject\Decimal;

class LicensePriceService
{
    public function calculatePriceFromOrder(ClientLicenseOrder $order): PricingBreakdown
    {
        $totalPriceNet       = new Decimal('0');
        $zero                = new Decimal('0');
        $period              = $order->getPeriod();
        $license             = $order->getLicense();

        $licensePriceNet = $period === PeriodEnum::YEAR->value
            ? $license->getPriceYear()->mul(new Decimal('12'))
            : $license->getPriceMonth();
        $licensePriceGross = $this->calculatePriceGrossFromNet($licensePriceNet);
        $totalPriceNet = $totalPriceNet->add($licensePriceNet);

        $extraPriceNet  = $zero;
        $extraPriceGross = $zero;
        $licenseExtraPriceNet = $this->calculateLicenseExtraPrice($order->getClient(), $license, $period);
        if ($licenseExtraPriceNet->getValueAsFloat() > 0) {
            $extraPriceNet  = $licenseExtraPriceNet;
            $extraPriceGross = $this->calculatePriceGrossFromNet($licenseExtraPriceNet);
            $totalPriceNet  = $totalPriceNet->add($licenseExtraPriceNet);
        }

        foreach ($order->getSelectedAddons() as $orderAddon) {
            /** @var ClientLicenseOrderAddon $orderAddon */
            $addonPriceNet = $period === PeriodEnum::YEAR->value
                ? $orderAddon->getAddon()->getPriceYear()->mul(new Decimal('12'))
                : $orderAddon->getAddon()->getPriceMonth();
            $orderAddon->applyPrice($addonPriceNet, $this->calculatePriceGrossFromNet($addonPriceNet));
            $totalPriceNet = $totalPriceNet->add($addonPriceNet);
        }

        $addonsExtraPriceNet  = $zero;
        $addonsExtraPriceGross = $zero;
        $addonsExtra = $this->calculateAddonsExtraPrice(
            $order->getClient(),
            $license,
            $order->getSelectedAddons()->map(fn(ClientLicenseOrderAddon $i) => $i->getAddon())->toArray()
        );
        if ($addonsExtra->getValueAsFloat() > 0) {
            $addonsExtraPriceNet  = $addonsExtra;
            $addonsExtraPriceGross = $this->calculatePriceGrossFromNet($addonsExtra);
            $totalPriceNet = $totalPriceNet->add($addonsExtra);
        }

        $devicesExtraPriceNet  = $zero;
        $devicesExtraPriceGross = $zero;
        foreach ($order->getAdditionalDevices() as $additionalDevice) {
            /** @var ClientLicenseOrderDevice $additionalDevice */
            $devicePriceNet = $period === PeriodEnum::YEAR->value
                ? $additionalDevice->getDevice()->getPriceYear()->mul(new Decimal('12'))->mul(new Decimal((string)$additionalDevice->getQuantity()))
                : $additionalDevice->getDevice()->getPriceMonth()->mul(new Decimal((string)$additionalDevice->getQuantity()));
            $additionalDevice->applyPrice($devicePriceNet, $this->calculatePriceGrossFromNet($devicePriceNet));
            $totalPriceNet = $totalPriceNet->add($devicePriceNet);

            $deviceExtraPriceNet = $this->calculateAdditionalDeviceExtraPrice(
                $order->getClient(), $license, $additionalDevice->getDevice()->getType(), $additionalDevice->getQuantity()
            );
            if ($deviceExtraPriceNet->getValueAsFloat() > 0) {
                $devicesExtraPriceNet  = $devicesExtraPriceNet->add($deviceExtraPriceNet);
                $devicesExtraPriceGross = $devicesExtraPriceGross->add($this->calculatePriceGrossFromNet($deviceExtraPriceNet));
                $totalPriceNet = $totalPriceNet->add($deviceExtraPriceNet);
            }
        }

        return new PricingBreakdown(
            priceNet:              $licensePriceNet,
            priceGross:            $licensePriceGross,
            extraPriceNet:         $extraPriceNet,
            extraPriceGross:       $extraPriceGross,
            addonsExtraPriceNet:   $addonsExtraPriceNet,
            addonsExtraPriceGross: $addonsExtraPriceGross,
            devicesExtraPriceNet:  $devicesExtraPriceNet,
            devicesExtraPriceGross: $devicesExtraPriceGross,
            totalPriceNet:         $totalPriceNet,
        );
    }

    public function calculateUpgradePriceFromOrder(ClientLicenseOrder $order): PricingBreakdown
    {
        $zero   = new Decimal('0');
        $period = $order->getPeriod();

        $extraPriceNet  = $zero;
        $extraPriceGross = $zero;
        $totalPriceNet  = $zero;
        $licenseExtraPriceNet = $this->calculateLicenseExtraPrice($order->getClient(), $order->getLicense(), $period);
        if ($licenseExtraPriceNet->getValueAsFloat() > 0) {
            $extraPriceNet  = $licenseExtraPriceNet;
            $extraPriceGross = $this->calculatePriceGrossFromNet($licenseExtraPriceNet);
            $totalPriceNet  = $totalPriceNet->add($licenseExtraPriceNet);
        }

        $addonBreakdown = $this->calculatePriceFromAddonsOrder($order, $order->getLicense());
        $totalPriceNet  = $totalPriceNet->add($addonBreakdown->totalPriceNet);

        return new PricingBreakdown(
            priceNet:              $zero,
            priceGross:            $zero,
            extraPriceNet:         $extraPriceNet,
            extraPriceGross:       $extraPriceGross,
            addonsExtraPriceNet:   $addonBreakdown->addonsExtraPriceNet,
            addonsExtraPriceGross: $addonBreakdown->addonsExtraPriceGross,
            devicesExtraPriceNet:  $addonBreakdown->devicesExtraPriceNet,
            devicesExtraPriceGross: $addonBreakdown->devicesExtraPriceGross,
            totalPriceNet:         $totalPriceNet,
        );
    }

    public function calculatePriceFromAddonsOrder(ClientLicenseOrder $order, License $license): AddonsPricingBreakdown
    {
        $zero          = new Decimal('0');
        $totalPriceNet = $zero;

        $addonsExtraPriceNet  = $zero;
        $addonsExtraPriceGross = $zero;
        $addonsExtra = $this->calculateAddonsExtraPrice(
            $order->getClient(),
            $license,
            $order->getSelectedAddons()->map(fn(ClientLicenseOrderAddon $i) => $i->getAddon())->toArray()
        );
        if ($addonsExtra->getValueAsFloat() > 0) {
            $addonsExtraPriceNet  = $addonsExtra;
            $addonsExtraPriceGross = $this->calculatePriceGrossFromNet($addonsExtra);
            $totalPriceNet = $totalPriceNet->add($addonsExtra);
        }

        $devicesExtraPriceNet  = $zero;
        $devicesExtraPriceGross = $zero;
        foreach ($order->getAdditionalDevices() as $additionalDevice) {
            /** @var ClientLicenseOrderDevice $additionalDevice */
            $deviceExtraPriceNet = $this->calculateAdditionalDeviceExtraPrice(
                $order->getClient(), $license, $additionalDevice->getDevice()->getType(), $additionalDevice->getQuantity()
            );
            if ($deviceExtraPriceNet->getValueAsFloat() > 0) {
                $devicesExtraPriceNet  = $devicesExtraPriceNet->add($deviceExtraPriceNet);
                $devicesExtraPriceGross = $devicesExtraPriceGross->add($this->calculatePriceGrossFromNet($deviceExtraPriceNet));
                $totalPriceNet = $totalPriceNet->add($deviceExtraPriceNet);
            }
        }

        return new AddonsPricingBreakdown(
            addonsExtraPriceNet:   $addonsExtraPriceNet,
            addonsExtraPriceGross: $addonsExtraPriceGross,
            devicesExtraPriceNet:  $devicesExtraPriceNet,
            devicesExtraPriceGross: $devicesExtraPriceGross,
            totalPriceNet:         $totalPriceNet,
        );
    }

    /**
     * @param Client $client - klient
     * @param License $newLicense - nowa licencja
     * @param string $selectedPeriod - nowy okres
     * @return Decimal
     */
    public function calculateLicenseExtraPrice(
        Client $client,
        License $newLicense,
        string $selectedPeriod
    ): Decimal
    {
        $extraPrice = new Decimal("0");
        $clientLatestLicense = $client->getLatestClientLicense();

        if ($newLicense->getId() !== $clientLatestLicense->getLicense()->getId()) {
            $clientActiveLicenses = $client->getActiveClientLicenses();

            /** @var ClientLicense $activeLicense */
            foreach ($clientActiveLicenses as $activeLicense) {
                $validFrom = (new \DateTime()) <= $activeLicense->getValidFrom() ? $activeLicense->getValidFrom() : new \DateTime();
                $fullMonthsToPay = $this->getFullPeriodsBetweenDates($validFrom, $activeLicense->getExpiredAt(), $activeLicense->getPeriod());
                $isMoreExpensive = $newLicense->getPriceByPeriod($selectedPeriod) >= $activeLicense->getLicense()->getPriceByPeriod($activeLicense->getPeriod());

                if (
                    $isMoreExpensive &&
                    $fullMonthsToPay > 0 &&
                    $activeLicense->getPeriod() === PeriodEnum::YEAR->value &&
                    $newLicense->getId() !== $activeLicense->getLicense()->getId()
                ) {
                    $extraPrice = $extraPrice->add(
                        $newLicense->getPriceYear()
                        ->sub($activeLicense->getLicense()->getPriceYear())
                        ->mul(new Decimal((string)$fullMonthsToPay))
                    );
                } elseif ($isMoreExpensive && $fullMonthsToPay > 0 && $activeLicense->getPeriod() === PeriodEnum::MONTH->value) {
                    $extraPrice = $extraPrice->add(
                        $newLicense->getPriceMonth()
                        ->sub($activeLicense->getLicense()->getPriceMonth())
                        ->mul(new Decimal((string)$fullMonthsToPay))
                    );
                }
            }
        }

        return $extraPrice;
    }

    /**
     * @param Client $client - klient
     * @param License $newLicense - nowa licencja lub ta sama w przypadku aktualizacji a nie przedłużania
     * @param array $newAddons - lista wybranych dodatków
     * @return Decimal
     */
    public function calculateAddonsExtraPrice(
        Client $client,
        License $newLicense,
        array $newAddons
    ): Decimal
    {
        $extraPrice = new Decimal("0");
        $clientActiveLicenses = $client->getActiveClientLicenses();

        /** @var ClientLicense $activeLicense */
        foreach ($clientActiveLicenses as $activeLicense) {
            $validFrom = (new \DateTime()) <= $activeLicense->getValidFrom() ? $activeLicense->getValidFrom() : new \DateTime();
            $fullMonthsToPay = $this->getFullPeriodsBetweenDates($validFrom, $activeLicense->getExpiredAt(), $activeLicense->getPeriod());
            if ($fullMonthsToPay === 0) {
                continue; //pomijamy jeśli nie mamy pełnych okresów
            }

            //doliczamy róznicę ceny na już zakupione dodatki jesli nowa licencja ma wyższe ceny na dodatki
            /** @var ClientAddon $clientAddon */
            foreach ($activeLicense->getAddons() as $clientAddon) {
                /** @var LicenseAddon $addonInNewLicense */
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
                    $extraPrice = $extraPrice->add(
                        $addonInNewLicense->getPriceByPeriod($activeLicense->getPeriod())
                            ->sub($clientAddon->getLicenseAddon()->getPriceByPeriod($activeLicense->getPeriod()))
                            ->mul(new Decimal((string)$fullMonthsToPay))
                    );
                }
            }

            //doliczamy cenę za nowe dodatki
            /** @var LicenseAddon $newAddon */
            foreach ($newAddons as $newAddon) {
                $existedAddon = $activeLicense->getAddons()->filter(
                    fn(ClientAddon $ca) =>
                        $ca->getLicenseAddon()->getType() === $newAddon->getType() &&
                        $ca->getLicenseAddon()->getCategory() === $newAddon->getCategory()
                )->first();

                //doliczamy tylko gdy dodatek nowy, bo za istniejące dodatki doliczamy różnicę wyżej
                if (!$existedAddon) {
                    $extraPrice = $extraPrice->add(
                        $newAddon->getPriceByPeriod($activeLicense->getPeriod())
                            ->mul(new Decimal((string)$fullMonthsToPay))
                    );
                }
            }
        }

        return $extraPrice;
    }

    /**
     * @param Client $client - klient
     * @param License $newLicense - nowa licencja lub ta sama w przypadku aktualizacji a nie przedłużania
     * @param string $newDeviceType - typ wubranego urządzenia
     * @param int $newDevicesQuantity - wybrana ilość dodatkowych urądzeń
     * @return Decimal
     */
    public function calculateAdditionalDeviceExtraPrice(
        Client $client,
        License $newLicense,
        string $newDeviceType,
        int $newDevicesQuantity
    ): Decimal
    {
        $extraPrice = new Decimal("0");
        $clientActiveLicenses = $client->getActiveClientLicenses();

        /** @var ClientLicense $activeLicense */
        foreach ($clientActiveLicenses as $activeLicense) {
            $validFrom = (new \DateTime()) <= $activeLicense->getValidFrom() ? $activeLicense->getValidFrom() : new \DateTime();
            $fullMonthsToPay = $this->getFullPeriodsBetweenDates($validFrom, $activeLicense->getExpiredAt(), $activeLicense->getPeriod());
            if ($fullMonthsToPay === 0) {
                continue; //pomijamy jeśli nie mamy pełnych okresów
            }

            $newDeviceLicenseEntity = $newLicense->getAdditionalDevices()->filter(
                fn(LicenseAdditionalDevice $d) => $d->getType() === $newDeviceType
            )->first();

            if (!$newDeviceLicenseEntity) {
                throw new LicenseDeviceNotAvailableException();
            }

            //doliczamy róznicę ceny na już zakupione dodatkowe urządzenia jesli nowa licencja ma wyższe ceny na dodatkowe urządzenia
            $existedDevices = $activeLicense->getAdditionalDevices()->filter(
                fn(ClientAdditionalDevice $cd) => $cd->getLicenseAdditionalDevice()->getType() === $newDeviceType
            );
            $existedDevicesQuantity = $existedDevices->count();

            if ($existedDevicesQuantity > 0) {
                $existedDevicesPrice = $existedDevices
                    ->first()
                    ->getLicenseAdditionalDevice()
                    ->getPriceByPeriod($activeLicense->getPeriod());

                if (
                    $newDeviceLicenseEntity->getPriceByPeriod($activeLicense->getPeriod()) >
                    $existedDevicesPrice &&
                    $existedDevices->first()->getLicenseAdditionalDevice()->getId() !== $newDeviceLicenseEntity->getId()
                ) {
                    $extraPrice = $extraPrice->add(
                        $newDeviceLicenseEntity->getPriceByPeriod($activeLicense->getPeriod())
                            ->sub($existedDevicesPrice)
                            ->mul(new Decimal((string)$fullMonthsToPay))
                            ->mul(new Decimal((string)$existedDevicesQuantity))
                    );
                }
            }

            //doliczamy cenę za nowe urządzenia jeżeli są
            $newDevicesToCreateQuantity = $newDevicesQuantity - $existedDevicesQuantity;
            if ($newDevicesToCreateQuantity > 0) {
                $extraPrice = $extraPrice->add(
                    $newDeviceLicenseEntity->getPriceByPeriod($activeLicense->getPeriod())
                        ->mul(new Decimal((string)$fullMonthsToPay))
                        ->mul(new Decimal((string)$newDevicesToCreateQuantity))
                );
            }

        }
        
        return $extraPrice;
    }

    public function getFullPeriodsBetweenDates(\DateTime $dateFrom, \DateTime $dateTo, string $period): int
    {
        if ($period === PeriodEnum::YEAR->value) {
            $start    = $dateFrom->getTimestamp();
            $end      = $dateTo->getTimestamp();
            $diff = $end - $start;
            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));

            $periods = $years*12 + $months;
        } else {
            $start    = $dateFrom->getTimestamp();
            $end      = $dateTo->getTimestamp();
            $periods = floor(ceil(abs($end - $start) / 86400) / $_ENV['MONTH_PERIOD_LENGTH']);
        }

        return (int) $periods;
    }

    public function calculatePriceGrossFromNet(Decimal $priceNet): Decimal
    {
        return $priceNet->mul(new Decimal("1.23"));
    }
}
