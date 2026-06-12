<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

final class ClientRegistered extends DomainEvent
{
    public function __construct(
        private readonly string $subdomain,
        private readonly string $email,
        private readonly string $userName,
        private readonly string $language,
        private readonly string $confirmationToken,
    ) {
        parent::__construct();
    }

    public function getSubdomain(): string         { return $this->subdomain; }
    public function getEmail(): string             { return $this->email; }
    public function getUserName(): string          { return $this->userName; }
    public function getLanguage(): string          { return $this->language; }
    public function getConfirmationToken(): string { return $this->confirmationToken; }
}
