<?php declare(strict_types=1);

namespace App\MerchantContext\Infrastructure\Persistence\Repository;

use App\MerchantContext\Domain\Repository\MerchantRepositoryInterface;
use App\MerchantContext\Domain\Entity\Merchant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MerchantRepository extends ServiceEntityRepository implements MerchantRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Merchant::class);
    }

    public function findOneBySpecialCode(string $specialCode): ?Merchant
    {
        return $this->createQueryBuilder('m')
            ->where('LOWER(m.specialCode) = :specialCode')
            ->setParameter('specialCode', strtolower($specialCode))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findDefault(): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.isDefault = :isDefault')
            ->setParameter('isDefault', true)
            ->getQuery()
            ->getResult();
    }
}
