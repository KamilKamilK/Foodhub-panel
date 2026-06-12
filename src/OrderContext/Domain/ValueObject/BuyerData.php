<?php declare(strict_types=1);

namespace App\OrderContext\Domain\ValueObject;

final class BuyerData
{
    public function __construct(
        public readonly string $name,
        public readonly string $vatNumber,
        public readonly string $street,
        public readonly string $house,
        public readonly ?string $flat,
        public readonly string $city,
        public readonly string $zip,
    ) {
    }
}
