<?php declare(strict_types=1);

namespace App\ClientContext\Application\Query\GetLicenseDetailsBySubDomain;

use App\Shared\Domain\ValueObject\Subdomain;

final class GetLicenseDetailsBySubDomainQuery
{
    public function __construct(
        private readonly Subdomain $subDomain,
        private readonly string $locale,
    ) {
    }

    public function getSubDomain(): Subdomain
    {
        return $this->subDomain;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
