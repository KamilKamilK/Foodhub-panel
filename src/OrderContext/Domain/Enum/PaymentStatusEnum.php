<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Enum;

enum PaymentStatusEnum: string
{
    case INITIALIZED = 'NEW';
    case PENDING = 'PENDING';
    case CANCELED = 'CANCELED';
    case COMPLETED = 'COMPLETED';
    case WAITING_FOR_CONFIRMATION = 'WAITING_FOR_CONFIRMATION';
}
