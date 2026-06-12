<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO;

use App\Shared\Application\DTO\BaseDTO;

abstract class AbstractUserRequest extends BaseDTO
{
    public string $email = '';
}
