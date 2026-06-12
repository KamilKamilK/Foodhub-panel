<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Repository;

use App\ClientContext\Domain\Entity\ClientLicense;

interface ClientLicenseRepositoryInterface
{
    public function create(ClientLicense $clientLicense): void;
}
