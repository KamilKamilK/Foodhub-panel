<?php declare(strict_types=1);

namespace App\Shared\Application\Service;

interface TransactionServiceInterface
{
    public function beginTransaction(): void;
    public function commit(): void;
    public function rollback(): void;
}
