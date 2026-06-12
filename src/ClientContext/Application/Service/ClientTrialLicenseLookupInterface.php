<?php declare(strict_types=1);

namespace App\ClientContext\Application\Service;

use App\LicenseContext\Domain\Entity\License;

interface ClientTrialLicenseLookupInterface
{
    public function findTrialLicense(): ?License;
}
