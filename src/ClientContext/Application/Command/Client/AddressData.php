<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\Client;

final class AddressData
{
    public function __construct(
        public readonly ?string $street,
        public readonly ?string $buildingNo,
        public readonly ?string $localNo,
        public readonly ?string $zipCode,
        public readonly ?string $city,
        public readonly ?string $country,
    ) {
    }
}
