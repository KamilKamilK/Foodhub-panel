<?php declare(strict_types=1);

namespace App\ClientContext\Application\Factory;

use App\ClientContext\Application\Command\Client\CompanyData;
use App\ClientContext\Domain\Entity\Company;

class CompanyFactory
{
    public function create(CompanyData $data): Company
    {
        return Company::create(
            name:               $data->name,
            shortName:          $data->shortName,
            taxIdNumber:        $data->taxIdNumber,
            registrationNumber: $data->registrationNumber,
        );
    }
}