<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Service;

use App\Shared\Application\Service\TransactionServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineTransactionService implements TransactionServiceInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function beginTransaction(): void
    {
        $this->entityManager->beginTransaction();
    }

    public function commit(): void
    {
        $this->entityManager->commit();
    }

    public function rollback(): void
    {
        $this->entityManager->rollback();
    }
}
