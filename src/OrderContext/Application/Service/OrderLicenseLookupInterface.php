<?php declare(strict_types=1);

namespace App\OrderContext\Application\Service;

use App\LicenseContext\Domain\Entity\License;
use App\LicenseContext\Domain\Entity\LicenseAdditionalDevice;
use App\LicenseContext\Domain\Entity\LicenseAddon;
use App\LicenseContext\Domain\Entity\LicenseSet;

interface OrderLicenseLookupInterface
{
    public function findLicenseById(int $id): ?License;

    public function findLicenseSetById(int $id): ?LicenseSet;

    public function findAddonByIdAndLicense(int $id, License $license): ?LicenseAddon;

    public function findAdditionalDeviceByIdAndLicense(int $id, License $license): ?LicenseAdditionalDevice;
}
