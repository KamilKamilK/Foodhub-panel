<?php declare(strict_types=1);

namespace App\Shared\Domain\Repository;

use App\Shared\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function findOneByEmail(string $email): ?User;

    public function findById(int $id): ?User;
}
