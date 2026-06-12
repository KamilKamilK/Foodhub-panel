<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Repository;

use App\OrderContext\Domain\Entity\Payment;

interface PaymentRepositoryInterface
{
    public function findClientPaymentByClientId(int $clientId): ?Payment;

    public function create(Payment $payment): void;
}
