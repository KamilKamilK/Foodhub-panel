<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Enum;

enum AddonTypeEnum: string
{
    case TAKEAWAY = 'TAKEAWAY';
    case UBER = 'UBER';
    case GLOVO = 'GLOVO';
    case UPMENU = 'UPMENU';
    case FOODHUBORDER = 'FOODHUBORDER';
}
