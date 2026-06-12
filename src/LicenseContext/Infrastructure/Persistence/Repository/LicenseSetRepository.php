<?php declare(strict_types=1);

namespace App\LicenseContext\Infrastructure\Persistence\Repository;

use App\LicenseContext\Domain\Repository\LicenseSetRepositoryInterface;
use App\LicenseContext\Domain\Entity\LicenseSet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LicenseSet|null find($id, $lockMode = null, $lockVersion = null)
 * @method LicenseSet|null findOneBy(array $criteria, array $orderBy = null)
 * @method LicenseSet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LicenseSetRepository extends ServiceEntityRepository implements LicenseSetRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicenseSet::class);
    }

    public function findById(int $id): ?LicenseSet
    {
        return $this->find($id);
    }

    public function create(LicenseSet $licenseSet): void
    {
        $this->_em->persist($licenseSet);
        $this->_em->flush();
    }

    public function update(LicenseSet $licenseSet): void
    {
        $this->_em->persist($licenseSet);
        $this->_em->flush();
    }

    public function findAllWithDependencies(): array
    {
        return $this->createQueryBuilder('s')
            ->addSelect('t')
            ->leftJoin('s.translations', 't')
            ->orderBy('s.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
