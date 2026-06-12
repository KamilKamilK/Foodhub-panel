<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Enum;

enum PeriodEnum: string
{
    case MONTH = 'MONTH';
    case YEAR = 'YEAR';
}
