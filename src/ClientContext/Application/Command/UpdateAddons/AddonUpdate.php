<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\UpdateAddons;

final class AddonUpdate
{
    public function __construct(
        public readonly string $addonType,
        public readonly string $addonCategory,
        public readonly bool $isActiveOnNextPeriod,
    ) {
    }
}
