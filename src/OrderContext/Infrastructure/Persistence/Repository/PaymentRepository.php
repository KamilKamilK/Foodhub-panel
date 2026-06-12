<?php declare(strict_types=1);

namespace App\OrderContext\Infrastructure\Persistence\Repository;

use App\OrderContext\Domain\Entity\Payment;
use App\OrderContext\Domain\Repository\PaymentRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PaymentRepository extends ServiceEntityRepository implements PaymentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    public function findClientPaymentByClientId(int $clientId): ?Payment
    {
        return $this->createQueryBuilder('cp')
            ->andWhere('IDENTITY(cp.client) = :clientId')
            ->setParameter('clientId', $clientId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function create(Payment $clientPayment): void
    {
        $this->_em->persist($clientPayment);
        $this->_em->flush();
    }
}
