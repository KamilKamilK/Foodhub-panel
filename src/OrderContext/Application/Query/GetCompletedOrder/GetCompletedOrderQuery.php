<?php declare(strict_types=1);

namespace App\OrderContext\Application\Query\GetCompletedOrder;

class GetCompletedOrderQuery
{
    public function __construct(private readonly string $orderId)
    {
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }
}
