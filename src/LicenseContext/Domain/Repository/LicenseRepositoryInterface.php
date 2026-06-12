<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Repository;

use App\LicenseContext\Domain\Entity\License;

interface LicenseRepositoryInterface
{
    public function findById(int $id): ?License;

    /**
     * @return License[]
     */
    public function findAllLicenses(): array;

    public function create(License $license): void;

    public function update(License $license): void;

    public function findTrialLicense(): ?License;

    /**
     * @return License[]
     */
    public function findAllActiveAndVisible(): array;
}