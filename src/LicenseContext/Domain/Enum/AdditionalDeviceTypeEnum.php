<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Enum;

enum AdditionalDeviceTypeEnum: string
{
    case POS = 'POS';
    case KDS = 'KDS';
}
