<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO;

use App\Shared\Application\DTO\BaseDTO;

class RegistrationRequest extends BaseDTO
{
    public ?UserDTO $user         = null;
    public ?string $type          = null;
    public array $agreementIds    = [];
    public bool $withProducts     = true;
}
