<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO;

final class CreateUserRequest extends AbstractUserRequest
{
    public ?string $subDomain = null;
    public bool $active = false;
}
