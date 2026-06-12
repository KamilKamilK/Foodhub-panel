<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\UpdateAddons;

final class DeviceUpdate
{
    public function __construct(
        public readonly string $deviceType,
        public readonly int $quantity,
    ) {
    }
}
