<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Repository;

use App\LicenseContext\Domain\Entity\LicenseSet;

interface LicenseSetRepositoryInterface
{
    public function findById(int $id): ?LicenseSet;

    public function create(LicenseSet $licenseSet): void;

    public function update(LicenseSet $licenseSet): void;

    /**
     * @return LicenseSet[]
     */
    public function findAllWithDependencies(): array;
}