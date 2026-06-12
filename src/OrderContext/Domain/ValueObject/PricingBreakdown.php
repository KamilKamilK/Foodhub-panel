<?php declare(strict_types=1);

namespace App\OrderContext\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Decimal;

final class PricingBreakdown
{
    public function __construct(
        public readonly Decimal $priceNet,
        public readonly Decimal $priceGross,
        public readonly Decimal $extraPriceNet,
        public readonly Decimal $extraPriceGross,
        public readonly Decimal $addonsExtraPriceNet,
        public readonly Decimal $addonsExtraPriceGross,
        public readonly Decimal $devicesExtraPriceNet,
        public readonly Decimal $devicesExtraPriceGross,
        public readonly Decimal $totalPriceNet,
    ) {
    }
}
