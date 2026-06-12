<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO;

use App\Shared\Application\DTO\BaseDTO;

class UserDTO extends BaseDTO
{
    public ?string $name        = null;
    public ?string $surname     = null;
    public ?string $phone       = null;
    public ?string $email       = null;
    public ?string $password    = null;
    public ?string $specialCode = null;
}
