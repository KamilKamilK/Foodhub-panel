<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Repository;

use App\LicenseContext\Domain\Entity\License;
use App\LicenseContext\Domain\Entity\LicenseAddon;

interface LicenseAddonRepositoryInterface
{
    public function create(LicenseAddon $licenseAddon): void;

    public function update(LicenseAddon $licenseAddon): void;

    public function findByIdAndLicense(int $id, License $license): ?LicenseAddon;
}
