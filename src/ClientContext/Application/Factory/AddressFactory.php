<?php declare(strict_types=1);

namespace App\ClientContext\Application\Factory;

use App\ClientContext\Application\Command\Client\AddressData;
use App\ClientContext\Domain\Entity\Address;

class AddressFactory
{
    public function create(AddressData $data): Address
    {
        return Address::create(
            street:     $data->street,
            buildingNo: $data->buildingNo,
            localNo:    $data->localNo,
            city:       $data->city,
            zipCode:    $data->zipCode,
            country:    $data->country,
        );
    }
}
