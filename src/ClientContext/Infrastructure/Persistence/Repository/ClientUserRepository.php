<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Persistence\Repository;

use App\ClientContext\Domain\Repository\ClientUserRepositoryInterface;
use App\ClientContext\Domain\Entity\ClientUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientUser[]    findAll()
 * @method ClientUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientUserRepository extends ServiceEntityRepository implements ClientUserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientUser::class);
    }

    public function create(ClientUser $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function update(ClientUser $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findOneByEmail(string $email): ?ClientUser
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByConfirmationToken(string $confirmationToken): ?ClientUser
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.confirmationToken = :confirmationToken')
            ->setParameter('confirmationToken', $confirmationToken)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function deleteByEmail(string $email): int
    {
        return $this->createQueryBuilder('u')
            ->delete()
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->execute();
    }
}
