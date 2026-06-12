<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\Client;

use App\Shared\Domain\ValueObject\Subdomain;

final class PatchClientCompanyCommand
{
    public function __construct(
        private readonly Subdomain $subDomain,
        private readonly CompanyData $companyData,
        private readonly AddressData $addressData,
    ) {
    }

    public function getSubDomain(): Subdomain    { return $this->subDomain; }
    public function getCompanyData(): CompanyData { return $this->companyData; }
    public function getAddressData(): AddressData { return $this->addressData; }
}
