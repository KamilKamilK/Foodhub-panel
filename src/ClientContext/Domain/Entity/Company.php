<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Entity;

class Company
{
    private ?string $name;
    private ?string $shortName;
    private ?string $taxIdNumber;
    private ?string $registrationNumber;

    public static function create(
        ?string $name,
        ?string $shortName,
        ?string $taxIdNumber,
        ?string $registrationNumber,
    ): self {
        $c = new self();
        $c->name               = $name;
        $c->shortName          = $shortName;
        $c->taxIdNumber        = $taxIdNumber;
        $c->registrationNumber = $registrationNumber;
        return $c;
    }

    public function getName(): ?string               { return $this->name; }
    public function getShortName(): ?string          { return $this->shortName; }
    public function getTaxIdNumber(): ?string        { return $this->taxIdNumber; }
    public function getRegistrationNumber(): ?string { return $this->registrationNumber; }
}
