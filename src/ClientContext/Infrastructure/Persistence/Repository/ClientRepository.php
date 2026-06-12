<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Persistence\Repository;

use App\ClientContext\Domain\Repository\ClientRepositoryInterface;
use App\ClientContext\Domain\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ClientRepository extends ServiceEntityRepository implements ClientRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findById(int $id): ?Client
    {
        return $this->find($id);
    }

    public function create(Client $client): void
    {
        $this->_em->persist($client);
        $this->_em->flush();
    }

    public function update(Client $client): void
    {
        $this->_em->persist($client);
        $this->_em->flush();
    }

    public function findOneBySubdomain(string $subdomain): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.subdomain = :subdomain')
            ->setParameter('subdomain', $subdomain)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneBySubdomainForLicenseDetails(string $subdomain): ?Client
    {
        return $this->createQueryBuilder('c')
            ->addSelect('cl', 'l', 'ca', 'cd', 'lt', 'la', 'ld')
            ->leftJoin('c.clientLicenses', 'cl')
            ->leftJoin('cl.license', 'l')
            ->leftJoin('cl.addons', 'ca')
            ->leftJoin('cl.additionalDevices', 'cd')
            ->leftJoin('l.translations', 'lt')
            ->leftJoin('ca.licenseAddon', 'la')
            ->leftJoin('cd.licenseAdditionalDevice', 'ld')
            ->andWhere('c.subdomain = :subdomain')
            ->setParameter('subdomain', $subdomain)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllWithDependencies(): array
    {
        return $this->createQueryBuilder('c')
            ->addSelect('cl', 'l', 'lt')
            ->leftJoin('c.clientLicenses', 'cl')
            ->leftJoin('cl.license', 'l')
            ->leftJoin('l.translations', 'lt')
            ->getQuery()
            ->getResult();
    }
}
