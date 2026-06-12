<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

final class AccountConfirmed extends DomainEvent
{
    public function __construct(
        private readonly string $email,
        private readonly string $locale,
    ) {
        parent::__construct();
    }

    public function getEmail(): string  { return $this->email; }
    public function getLocale(): string { return $this->locale; }
}
