<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Enum;

enum LicenseOrderTypeEnum: string
{
    case NEW_LICENSE = 'NEW_LICENSE';
    case NEW_ADDONS = 'NEW_ADDONS';
    case UPGRADE_LICENSE = 'UPGRADE_LICENSE';
    case DOWNGRADE_LICENSE = 'DOWNGRADE_LICENSE';
    case LICENSE_AUTO_RENEWAL = 'LICENSE_AUTO_RENEWAL';
}
