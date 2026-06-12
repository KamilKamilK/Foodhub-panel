<?php declare(strict_types=1);

namespace App\OrderContext\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Decimal;

final class AddonsPricingBreakdown
{
    public function __construct(
        public readonly Decimal $addonsExtraPriceNet,
        public readonly Decimal $addonsExtraPriceGross,
        public readonly Decimal $devicesExtraPriceNet,
        public readonly Decimal $devicesExtraPriceGross,
        public readonly Decimal $totalPriceNet,
    ) {
    }
}
