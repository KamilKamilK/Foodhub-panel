<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\Client;

final class CompanyData
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?string $shortName,
        public readonly ?string $taxIdNumber,
        public readonly ?string $registrationNumber,
    ) {
    }
}
