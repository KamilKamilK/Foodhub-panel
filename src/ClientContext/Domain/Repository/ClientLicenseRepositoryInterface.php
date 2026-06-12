<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Repository;

use App\ClientContext\Domain\Entity\Client;
use App\ClientContext\Domain\Entity\ClientLicense;

interface ClientLicenseRepositoryInterface
{
    public function create(ClientLicense $clientLicense): void;

    public function update(ClientLicense $clientLicense): void;

    public function findById(int $id): ?ClientLicense;

    /** @return ClientLicense[] */
    public function findActiveByClient(Client $client): array;
}
