<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

final class PaymentCompleted extends DomainEvent
{
    public function __construct(
        private readonly string $orderId,
        private readonly string $subdomain,
        private readonly string $orderType,
        private readonly float $totalAmount,
    ) {
        parent::__construct();
    }

    public function getOrderId(): string    { return $this->orderId; }
    public function getSubdomain(): string  { return $this->subdomain; }
    public function getOrderType(): string  { return $this->orderType; }
    public function getTotalAmount(): float { return $this->totalAmount; }
}
