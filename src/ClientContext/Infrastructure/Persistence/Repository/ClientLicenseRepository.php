<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Persistence\Repository;

use App\ClientContext\Domain\Entity\Client;
use App\ClientContext\Domain\Entity\ClientLicense;
use App\ClientContext\Domain\Repository\ClientLicenseRepositoryInterface;
use App\OrderContext\Domain\Repository\ClientLicenseRepositoryInterface as OrderClientLicenseRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ClientLicenseRepository extends ServiceEntityRepository implements ClientLicenseRepositoryInterface, OrderClientLicenseRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientLicense::class);
    }

    public function create(ClientLicense $clientLicense): void
    {
        $this->_em->persist($clientLicense);
        $this->_em->flush();
    }

    public function update(ClientLicense $clientLicense): void
    {
        $this->_em->persist($clientLicense);
        $this->_em->flush();
    }

    public function findById(int $id): ?ClientLicense
    {
        return $this->find($id);
    }

    public function findActiveByClient(Client $client): array
    {
        return $this->createQueryBuilder('cl')
            ->andWhere('cl.client = :client')
            ->andWhere('cl.expiredAt >= :now')
            ->setParameter('client', $client)
            ->setParameter('now', new \DateTime())
            ->orderBy('cl.validFrom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
