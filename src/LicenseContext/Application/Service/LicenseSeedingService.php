<?php declare(strict_types=1);

namespace App\LicenseContext\Application\Service;

use App\LicenseContext\Domain\Entity\License;
use App\LicenseContext\Domain\Entity\LicenseAddon;
use App\LicenseContext\Domain\Entity\LicenseAddonTranslation;
use App\LicenseContext\Domain\Entity\LicenseAdditionalDevice;
use App\LicenseContext\Domain\Entity\LicenseAdditionalDeviceTranslation;
use App\LicenseContext\Domain\Entity\LicenseBonus;
use App\LicenseContext\Domain\Entity\LicenseBonusTranslation;
use App\LicenseContext\Domain\Entity\LicenseTranslation;
use App\LicenseContext\Domain\Repository\LicenseRepositoryInterface;
use App\Shared\Domain\ValueObject\Decimal;

class LicenseSeedingService
{
    public function __construct(
        private readonly LicenseRepositoryInterface $licenseRepository,
    ) {
    }

    public function seedFromData(array $licenseDataSet): void
    {
        foreach ($licenseDataSet as $licenseData) {
            $license = License::create(
                priceMonth: $licenseData['priceMonth'] ? new Decimal($licenseData['priceMonth']) : null,
                priceYear:  $licenseData['priceYear'] ? new Decimal($licenseData['priceYear']) : null,
                currency:   $licenseData['currency'],
            );
            $license->configureCapacity($licenseData['includedPoses'], $licenseData['menuLimit'], $licenseData['position']);
            $licenseData['isActive'] ? $license->activate() : $license->deactivate();
            if (!$licenseData['isVisible']) { $license->hide(); }
            if ($licenseData['isTrial']) { $license->markAsTrial(); }
            if (!empty($licenseData['includedFoodHubOrder'])) { $license->enableFoodHubOrder(); }

            foreach ($licenseData['translations'] as $locale => $translationData) {
                $license->addTranslation(
                    LicenseTranslation::create($locale, $translationData['name'], $translationData['description'])
                );
            }

            foreach ($licenseData['addons'] as $addonData) {
                $addon = LicenseAddon::create(
                    type:       $addonData['type'],
                    category:   $addonData['category'],
                    priceMonth: $addonData['priceMonth'] ? new Decimal($addonData['priceMonth']) : null,
                    priceYear:  $addonData['priceYear'] ? new Decimal($addonData['priceYear']) : null,
                    currency:   $addonData['currency'],
                );

                foreach ($addonData['translations'] as $locale => $translationData) {
                    $addon->addTranslation(LicenseAddonTranslation::create($locale, $translationData['name']));
                }

                $license->addAddon($addon);
            }

            foreach ($licenseData['additionalDevices'] as $additionalDeviceData) {
                $additionalDevice = LicenseAdditionalDevice::create(
                    type:       $additionalDeviceData['type'],
                    priceMonth: $additionalDeviceData['priceMonth'] ? new Decimal($additionalDeviceData['priceMonth']) : null,
                    priceYear:  $additionalDeviceData['priceYear'] ? new Decimal($additionalDeviceData['priceYear']) : null,
                    currency:   $additionalDeviceData['currency'],
                );

                foreach ($additionalDeviceData['translations'] as $locale => $translationData) {
                    $additionalDevice->addTranslation(
                        LicenseAdditionalDeviceTranslation::create($locale, $translationData['name'])
                    );
                }

                $license->addAdditionalDevice($additionalDevice);
            }

            foreach ($licenseData['bonuses'] as $bonusData) {
                $bonus = new LicenseBonus();

                foreach ($bonusData['translations'] as $locale => $translationData) {
                    $bonus->addTranslation(LicenseBonusTranslation::create($locale, $translationData['name']));
                }

                $license->addBonus($bonus);
            }

            $this->licenseRepository->create($license);
        }
    }
}
