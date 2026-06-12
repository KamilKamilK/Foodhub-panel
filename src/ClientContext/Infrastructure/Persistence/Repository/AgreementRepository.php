<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Persistence\Repository;

use App\ClientContext\Domain\Repository\AgreementRepositoryInterface;
use App\ClientContext\Domain\Entity\Agreement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AgreementRepository extends ServiceEntityRepository implements AgreementRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agreement::class);
    }

    public function findByLocale(string $locale): array
    {
        return $this->findBy(
            ['locale' => strtolower($locale)],
            ['required' => 'DESC', 'id' => 'ASC'],
        );
    }

    public function findByIds(array $agreementsIds): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id IN (:agreementsIds)')
            ->setParameter('agreementsIds', $agreementsIds)
            ->getQuery()
            ->getResult();
    }
}
