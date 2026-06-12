<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Repository;

use App\Shared\Domain\Entity\User;
use App\Shared\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findById(int $id): ?User
    {
        return $this->find($id);
    }
}
