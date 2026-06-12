<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\ClientUser;

use App\ClientContext\Application\DTO\DeleteUserRequest;

class DeleteCommand
{
    public function __construct(private readonly DeleteUserRequest $deleteUserRequest)
    {
    }

    public function getDeleteUserRequest(): DeleteUserRequest
    {
        return $this->deleteUserRequest;
    }
}
