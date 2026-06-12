<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\ResendMail;

final class ResendMailCommand
{
    public function __construct(
        private readonly string $email,
        private readonly string $locale,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
