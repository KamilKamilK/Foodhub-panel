<?php declare(strict_types=1);

namespace App\LicenseContext\Infrastructure\Persistence\Repository;

use App\LicenseContext\Domain\Entity\License;
use App\LicenseContext\Domain\Entity\LicenseAdditionalDevice;
use App\LicenseContext\Domain\Repository\LicenseAdditionalDeviceRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LicenseAdditionalDeviceRepository extends ServiceEntityRepository implements LicenseAdditionalDeviceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LicenseAdditionalDevice::class);
    }

    public function create(LicenseAdditionalDevice $licenseAdditionalDevice): void
    {
        $this->_em->persist($licenseAdditionalDevice);
        $this->_em->flush();
    }

    public function update(LicenseAdditionalDevice $licenseAdditionalDevice): void
    {
        $this->_em->persist($licenseAdditionalDevice);
        $this->_em->flush();
    }

    public function findByIdAndLicense(int $id, License $license): ?LicenseAdditionalDevice
    {
        return $this->findOneBy(['id' => $id, 'license' => $license]);
    }
}
