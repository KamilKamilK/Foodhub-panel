<?php declare(strict_types=1);

namespace App\OrderContext\Application\Query\GetOrder;

class GetOrderQuery
{
    private string $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }
}
