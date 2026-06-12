<?php declare(strict_types=1);

namespace App\OrderContext\Infrastructure\Persistence\Lookup;

use App\ClientContext\Domain\Entity\Client;
use App\ClientContext\Domain\Repository\ClientRepositoryInterface;
use App\OrderContext\Application\Service\OrderClientLookupInterface;

class OrderClientLookup implements OrderClientLookupInterface
{
    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository,
    ) {
    }

    public function findBySubdomainWithLicenseDetails(string $subdomain): ?Client
    {
        return $this->clientRepository->findOneBySubdomainForLicenseDetails($subdomain);
    }
}
