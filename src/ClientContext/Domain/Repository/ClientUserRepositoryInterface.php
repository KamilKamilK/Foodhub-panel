<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Repository;

use App\ClientContext\Domain\Entity\ClientUser;

interface ClientUserRepositoryInterface
{
    public function create(ClientUser $user): void;

    public function update(ClientUser $user): void;

    public function findOneByEmail(string $email): ?ClientUser;

    public function findOneByConfirmationToken(string $confirmationToken): ?ClientUser;

    public function deleteByEmail(string $email): int;
}