<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO;

use App\Shared\Application\DTO\BaseDTO;

final class PatchClientCompanyRequest extends BaseDTO
{
    public ?string $subDomain           = null;
    public ?string $name                = null;
    public ?string $shortName           = null;
    public ?string $taxIdNumber         = null;
    public ?string $registrationNumber  = null;
    public ?string $street              = null;
    public ?string $buildingNo          = null;
    public ?string $localNo             = null;
    public ?string $zipCode             = null;
    public ?string $city                = null;
    public ?string $country             = null;
}
