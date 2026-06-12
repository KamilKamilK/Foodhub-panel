<?php declare(strict_types=1);

namespace App\OrderContext\Application\DTO;

use App\Shared\Application\DTO\BaseDTO;

class PayUPaymentsDataDTO extends BaseDTO
{
    public function __construct(
        public readonly string $posId,
        public readonly array $paymentMethods,
    ) {
    }
}
