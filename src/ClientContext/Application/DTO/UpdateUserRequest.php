<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO;

final class UpdateUserRequest extends AbstractUserRequest
{
    public ?string $previousEmail = null;
}
