<?php declare(strict_types=1);

namespace App\ClientContext\Application\Service;

interface TenantApiClientInterface
{
    public function install(array $payload, string $subdomain, string $language): string;
}
