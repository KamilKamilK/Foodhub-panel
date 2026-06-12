<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO\License;

use App\LicenseContext\Domain\Entity\LicenseAdditionalDevice;

final class DeviceProvision
{
    public function __construct(
        public readonly LicenseAdditionalDevice $device,
        public readonly int $quantity,
    ) {
    }
}
