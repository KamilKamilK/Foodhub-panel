<?php declare(strict_types=1);

namespace App\MerchantContext\Domain\Entity;

class Merchant
{
    private ?int $id;
    private string $email;
    private ?string $phone;
    private string $firstName;
    private string $lastName;
    private ?string $specialCode;
    private bool $isDefault;

    public function __construct()
    {
        $this->isDefault = false;
    }

    public static function create(
        string $email,
        ?string $phone,
        string $firstName,
        string $lastName,
        ?string $specialCode,
        bool $isDefault,
    ): self {
        $m = new self();
        $m->email       = $email;
        $m->phone       = $phone;
        $m->firstName   = $firstName;
        $m->lastName    = $lastName;
        $m->specialCode = $specialCode;
        $m->isDefault   = $isDefault;
        return $m;
    }

    public function getId(): ?int             { return $this->id; }
    public function getEmail(): string        { return $this->email; }
    public function getPhone(): ?string       { return $this->phone; }
    public function getFirstName(): string    { return $this->firstName; }
    public function getLastName(): string     { return $this->lastName; }
    public function getSpecialCode(): ?string { return $this->specialCode; }
    public function getIsDefault(): bool      { return $this->isDefault; }
}
