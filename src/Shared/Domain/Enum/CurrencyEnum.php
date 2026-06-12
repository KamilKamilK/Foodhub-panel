<?php declare(strict_types=1);

namespace App\Shared\Domain\Enum;

enum CurrencyEnum: string
{
    case PLN = 'PLN';
    case EUR = 'EUR';
    case USD = 'USD';
}
