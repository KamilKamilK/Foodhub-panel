<?php declare(strict_types=1);

namespace App\LicenseContext\Application\Service;

use App\ClientContext\Domain\Entity\ClientLicense;
use App\ClientContext\Domain\Repository\ClientLicenseRepositoryInterface;
use App\ClientContext\Domain\Repository\ClientRepositoryInterface;
use App\LicenseContext\Domain\Repository\LicenseRepositoryInterface;

class TrialLicenseAssignmentService
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private LicenseRepositoryInterface $licenseRepository,
        private ClientLicenseRepositoryInterface $clientLicenseRepository,
        private int $trialPeriodLength,
    ) {
    }

    public function assignToClientsWithoutLicense(): void
    {
        $trialLicense = $this->licenseRepository->findTrialLicense();

        foreach ($this->clientRepository->findAllWithDependencies() as $client) {
            if ($client->getClientLicenses()->count() > 0) {
                continue;
            }

            $clientLicense = ClientLicense::forTrial(
                license:      $trialLicense,
                client:       $client,
                durationDays: $this->trialPeriodLength,
                specialCode:  null,
            );

            $this->clientLicenseRepository->create($clientLicense);
        }
    }
}
