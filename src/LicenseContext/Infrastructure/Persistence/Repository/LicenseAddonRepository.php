<?php declare(strict_types=1);

namespace App\LicenseContext\Infrastructure\Persistence\Repository;

use App\LicenseContext\Domain\Entity\License;
use App\LicenseContext\Domain\Entity\LicenseAddon;
use App\LicenseContext\Domain\Repository\LicenseAddonRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LicenseAddonRepository extends ServiceEntityRepository implements LicenseAddonRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicenseAddon::class);
    }

    public function create(LicenseAddon $licenseAddon): void
    {
        $this->_em->persist($licenseAddon);
        $this->_em->flush();
    }

    public function update(LicenseAddon $licenseAddon): void
    {
        $this->_em->persist($licenseAddon);
        $this->_em->flush();
    }

    public function findByIdAndLicense(int $id, License $license): ?LicenseAddon
    {
        return $this->findOneBy(['id' => $id, 'license' => $license]);
    }
}
