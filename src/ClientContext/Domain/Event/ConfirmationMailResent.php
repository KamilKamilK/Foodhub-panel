<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

final class ConfirmationMailResent extends DomainEvent
{
    public function __construct(
        private readonly string $email,
        private readonly string $locale,
        private readonly string $confirmationToken,
        private readonly string $activationLink,
    ) {
        parent::__construct();
    }

    public function getEmail(): string             { return $this->email; }
    public function getLocale(): string            { return $this->locale; }
    public function getConfirmationToken(): string { return $this->confirmationToken; }
    public function getActivationLink(): string    { return $this->activationLink; }
}
