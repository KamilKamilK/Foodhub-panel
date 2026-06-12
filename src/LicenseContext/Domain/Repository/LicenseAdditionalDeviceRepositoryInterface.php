<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Repository;

use App\LicenseContext\Domain\Entity\License;
use App\LicenseContext\Domain\Entity\LicenseAdditionalDevice;

interface LicenseAdditionalDeviceRepositoryInterface
{
    public function create(LicenseAdditionalDevice $licenseAdditionalDevice): void;

    public function update(LicenseAdditionalDevice $licenseAdditionalDevice): void;

    public function findByIdAndLicense(int $id, License $license): ?LicenseAdditionalDevice;
}
