<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\ConfirmAccount;

final class ConfirmAccountCommand
{
    public function __construct(
        private readonly string $confirmationToken,
        private readonly string $locale,
    ) {
    }

    public function getConfirmationToken(): string { return $this->confirmationToken; }
    public function getLocale(): string            { return $this->locale; }
}
