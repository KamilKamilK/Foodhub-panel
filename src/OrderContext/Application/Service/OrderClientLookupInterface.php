<?php declare(strict_types=1);

namespace App\OrderContext\Application\Service;

use App\ClientContext\Domain\Entity\Client;

interface OrderClientLookupInterface
{
    public function findBySubdomainWithLicenseDetails(string $subdomain): ?Client;
}
