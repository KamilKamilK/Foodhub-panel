<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\Setup\DTO;

class Db
{
    public function __construct(
        private readonly string $name,
        private readonly string $user,
        private readonly string $password,
    ) {
    }

    public function getName(): string     { return $this->name; }
    public function getUser(): string     { return $this->user; }
    public function getPassword(): string { return $this->password; }
}
