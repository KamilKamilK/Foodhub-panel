<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Enum;

enum PaymentMethodTypeEnum: string
{
    case CARD_TOKEN = 'CARD_TOKEN';
    case BLIK = 'BLIK';
    case OTHER = 'OTHER';
}
