<?php declare(strict_types=1);

namespace App\LicenseContext\Infrastructure\Persistence\Repository;

use App\LicenseContext\Domain\Repository\LicenseRepositoryInterface;
use App\LicenseContext\Domain\Entity\License;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method License|null find($id, $lockMode = null, $lockVersion = null)
 * @method License|null findOneBy(array $criteria, array $orderBy = null)
 * @method License[]    findAll()
 * @method License[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LicenseRepository extends ServiceEntityRepository implements LicenseRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, License::class);
    }

    public function findById(int $id): ?License
    {
        return $this->find($id);
    }

    public function findAllLicenses(): array
    {
        return $this->findAll();
    }

    public function create(License $license): void
    {
        $this->_em->persist($license);
        $this->_em->flush();
    }

    public function update(License $license): void
    {
        $this->_em->persist($license);
        $this->_em->flush();
    }

    public function findTrialLicense(): ?License
    {
        return $this->createQueryBuilder('l')
            ->where('l.isTrial = :isTrial')
            ->setParameter('isTrial', true)
            ->orderBy('l.position', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllActiveAndVisible(): array
    {
        return $this->createQueryBuilder('l')
            ->addSelect('t', 'ad', 'a', 'b', 'adt', 'at', 'bt')
            ->leftJoin('l.translations', 't')
            ->leftJoin('l.additionalDevices', 'ad')
            ->leftJoin('l.addons', 'a')
            ->leftJoin('l.bonuses', 'b')
            ->leftJoin('ad.translations', 'adt')
            ->leftJoin('a.translations', 'at')
            ->leftJoin('b.translations', 'bt')
            ->where('l.isVisible = :true')
            ->andWhere('l.isActive = :true')
            ->setParameter('true', true)
            ->orderBy('l.position', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
