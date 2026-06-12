<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\Setup\DTO;

class User
{
    public function __construct(
        private readonly string $name,
        private readonly string $surname,
        private readonly string $phone,
        private readonly string $email,
        private readonly string $password,
        private readonly ?string $specialCode = null,
    ) {
    }

    public function getName(): string       { return $this->name; }
    public function getSurname(): string    { return $this->surname; }
    public function getPhone(): string      { return $this->phone; }
    public function getEmail(): string      { return $this->email; }
    public function getPassword(): string   { return $this->password; }
    public function getSpecialCode(): ?string { return $this->specialCode; }

    public function toArray(): array
    {
        return [
            'name'        => $this->name,
            'surname'     => $this->surname,
            'phone'       => $this->phone,
            'email'       => $this->email,
            'password'    => $this->password,
            'specialCode' => $this->specialCode,
        ];
    }
}
