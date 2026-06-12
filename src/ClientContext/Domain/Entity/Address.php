<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Entity;

class Address
{
    private ?string $street;
    private ?string $buildingNo;
    private ?string $localNo;
    private ?string $zipCode;
    private ?string $city;
    private ?string $country;

    public static function create(
        ?string $street,
        ?string $buildingNo,
        ?string $localNo,
        ?string $city,
        ?string $zipCode,
        ?string $country,
    ): self {
        $a = new self();
        $a->street     = $street;
        $a->buildingNo = $buildingNo;
        $a->localNo    = $localNo;
        $a->city       = $city;
        $a->zipCode    = $zipCode;
        $a->country    = $country;
        return $a;
    }

    public function getStreet(): ?string     { return $this->street; }
    public function getBuildingNo(): ?string { return $this->buildingNo; }
    public function getLocalNo(): ?string    { return $this->localNo; }
    public function getZipCode(): ?string    { return $this->zipCode; }
    public function getCity(): ?string       { return $this->city; }
    public function getCountry(): ?string    { return $this->country; }

    public function getAddress(): string
    {
        return trim(implode(' ', array_filter([
            $this->street,
            $this->buildingNo,
            $this->localNo,
        ], static fn (?string $part): bool => $part !== null && $part !== '')));
    }
}
