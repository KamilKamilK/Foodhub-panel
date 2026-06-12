<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\ClientUser;

use App\ClientContext\Application\DTO\CreateUserRequest;

class CreateCommand
{
    public function __construct(private readonly CreateUserRequest $createUserRequest)
    {
    }

    public function getCreateUserRequest(): CreateUserRequest
    {
        return $this->createUserRequest;
    }
}
