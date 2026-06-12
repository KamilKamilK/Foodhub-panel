<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\ClientUser;

use App\ClientContext\Application\DTO\UpdateUserRequest;

class UpdateCommand
{
    public function __construct(private readonly UpdateUserRequest $updateUserRequest)
    {
    }

    public function getUpdateUserRequest(): UpdateUserRequest
    {
        return $this->updateUserRequest;
    }
}
