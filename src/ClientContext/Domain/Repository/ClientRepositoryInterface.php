<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Repository;

use App\ClientContext\Domain\Entity\Client;

interface ClientRepositoryInterface
{
    public function findById(int $id): ?Client;

    public function create(Client $client): void;

    public function update(Client $client): void;

    public function findOneBySubdomain(string $subdomain): ?Client;

    public function findOneBySubdomainForLicenseDetails(string $subdomain): ?Client;

    /** @return Client[] */
    public function findAllWithDependencies(): array;
}
