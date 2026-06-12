<?php declare(strict_types=1);

namespace App\ClientContext\Application\Query\GetUrlByEmail;

class GetUrlByEmailQuery
{
    public function __construct(private readonly string $email)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
