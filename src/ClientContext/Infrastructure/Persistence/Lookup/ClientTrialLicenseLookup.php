<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Persistence\Lookup;

use App\ClientContext\Application\Service\ClientTrialLicenseLookupInterface;
use App\LicenseContext\Domain\Entity\License;
use App\LicenseContext\Domain\Repository\LicenseRepositoryInterface;

class ClientTrialLicenseLookup implements ClientTrialLicenseLookupInterface
{
    public function __construct(
        private readonly LicenseRepositoryInterface $licenseRepository,
    ) {
    }

    public function findTrialLicense(): ?License
    {
        return $this->licenseRepository->findTrialLicense();
    }
}
