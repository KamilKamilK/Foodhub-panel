<?php declare(strict_types=1);

namespace App\LicenseContext\Application\Service;

use App\LicenseContext\Domain\Entity\LicenseSet;
use App\LicenseContext\Domain\Entity\LicenseSetTranslation;
use App\LicenseContext\Domain\Repository\LicenseSetRepositoryInterface;
use App\Shared\Domain\ValueObject\Decimal;

class LicenseSetSeedingService
{
    public function __construct(
        private readonly LicenseSetRepositoryInterface $licenseSetRepository,
    ) {
    }

    public function seedFromData(array $setDataCollection): void
    {
        foreach ($setDataCollection as $setData) {
            $set = LicenseSet::create(
                price:    $setData['price'] ? new Decimal($setData['price']) : null,
                currency: $setData['currency'],
                position: $setData['position'],
            );

            foreach ($setData['translations'] as $locale => $translationData) {
                $set->addTranslation(LicenseSetTranslation::create(
                    locale:      $locale,
                    name:        $translationData['name'],
                    description: $translationData['description'],
                    btnName:     $translationData['btnName'],
                    btnUrl:      $translationData['btnUrl'],
                ));
            }

            $this->licenseSetRepository->create($set);
        }
    }
}
