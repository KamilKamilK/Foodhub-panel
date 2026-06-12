<?php declare(strict_types=1);

namespace App\OrderContext\Infrastructure\Persistence\Repository;

use App\OrderContext\Domain\Repository\ClientLicenseOrderRepositoryInterface;
use App\OrderContext\Domain\Entity\ClientLicenseOrder;
use App\OrderContext\Domain\Enum\PaymentStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientLicenseOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientLicenseOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientLicenseOrder[]    findAll()
 * @method ClientLicenseOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientLicenseOrderRepository extends ServiceEntityRepository implements ClientLicenseOrderRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientLicenseOrder::class);
    }

    public function create(ClientLicenseOrder $clientLicenseOrder): void
    {
        $this->_em->persist($clientLicenseOrder);
        $this->_em->flush();
    }

    public function update(ClientLicenseOrder $clientLicenseOrder): void
    {
        $this->_em->persist($clientLicenseOrder);
        $this->_em->flush();
    }

    public function findOneByOrderId(string $orderId): ?ClientLicenseOrder
    {
        return $this->createQueryBuilder('o')
            ->addSelect('oa', 'oad', 'p', 'c', 'cl', 'cad', 'ca')
            ->leftJoin('o.additionalDevices', 'oad')
            ->leftJoin('o.selectedAddons', 'oa')
            ->leftJoin('o.payment', 'p')
            ->leftJoin('o.client', 'c')
            ->leftJoin('c.clientLicenses', 'cl')
            ->leftJoin('cl.additionalDevices', 'cad')
            ->leftJoin('cl.addons', 'ca')
            ->andWhere('o.orderId = :orderId')
            ->setParameter('orderId', $orderId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneCompletedByOrderId(string $orderId): ?ClientLicenseOrder
    {
        return $this->createQueryBuilder('o')
            ->addSelect('oa', 'oad', 'p', 'c')
            ->leftJoin('o.additionalDevices', 'oad')
            ->leftJoin('o.selectedAddons', 'oa')
            ->leftJoin('o.payment', 'p')
            ->leftJoin('o.client', 'c')
            ->where('o.orderId = :orderId')
            ->andWhere('p.status = :status')
            ->setParameter('orderId', $orderId)
            ->setParameter('status', PaymentStatusEnum::COMPLETED->value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOrdersByDocumentNumberPattern(string $numberPattern): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.invoiceNo LIKE :number')
            ->setParameter('number', $numberPattern)
            ->getQuery()
            ->getResult();
    }

    public function findInvoices(string $subdomain): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.client', 'c')
            ->where('o.invoiceNo IS NOT NULL')
            ->andWhere('c.subdomain = :subdomain')
            ->setParameter('subdomain', $subdomain)
            ->orderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
