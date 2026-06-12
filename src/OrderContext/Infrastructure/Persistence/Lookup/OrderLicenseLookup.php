<?php declare(strict_types=1);

namespace App\OrderContext\Infrastructure\Persistence\Lookup;

use App\LicenseContext\Domain\Entity\License;
use App\LicenseContext\Domain\Entity\LicenseAdditionalDevice;
use App\LicenseContext\Domain\Entity\LicenseAddon;
use App\LicenseContext\Domain\Entity\LicenseSet;
use App\LicenseContext\Domain\Repository\LicenseAdditionalDeviceRepositoryInterface;
use App\LicenseContext\Domain\Repository\LicenseAddonRepositoryInterface;
use App\LicenseContext\Domain\Repository\LicenseRepositoryInterface;
use App\LicenseContext\Domain\Repository\LicenseSetRepositoryInterface;
use App\OrderContext\Application\Service\OrderLicenseLookupInterface;

class OrderLicenseLookup implements OrderLicenseLookupInterface
{
    public function __construct(
        private readonly LicenseRepositoryInterface $licenseRepository,
        private readonly LicenseSetRepositoryInterface $licenseSetRepository,
        private readonly LicenseAddonRepositoryInterface $licenseAddonRepository,
        private readonly LicenseAdditionalDeviceRepositoryInterface $additionalDeviceRepository,
    ) {
    }

    public function findLicenseById(int $id): ?License
    {
        return $this->licenseRepository->findById($id);
    }

    public function findLicenseSetById(int $id): ?LicenseSet
    {
        return $this->licenseSetRepository->findById($id);
    }

    public function findAddonByIdAndLicense(int $id, License $license): ?LicenseAddon
    {
        return $this->licenseAddonRepository->findByIdAndLicense($id, $license);
    }

    public function findAdditionalDeviceByIdAndLicense(int $id, License $license): ?LicenseAdditionalDevice
    {
        return $this->additionalDeviceRepository->findByIdAndLicense($id, $license);
    }
}
