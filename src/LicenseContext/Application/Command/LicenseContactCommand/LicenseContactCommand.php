<?php declare(strict_types=1);

namespace App\LicenseContext\Application\Command\LicenseContactCommand;

use App\Shared\Domain\ValueObject\Subdomain;

class LicenseContactCommand
{
    public function __construct(
        private readonly Subdomain $subdomain,
        private readonly string $contactPhone,
    ) {
    }

    public function getSubdomain(): Subdomain
    {
        return $this->subdomain;
    }

    public function getContactPhone(): string
    {
        return $this->contactPhone;
    }
}
