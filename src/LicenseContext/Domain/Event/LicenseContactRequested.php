<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

final class LicenseContactRequested extends DomainEvent
{
    public function __construct(
        private readonly string $subdomain,
        private readonly string $clientName,
        private readonly string $clientEmail,
        private readonly string $clientPhone,
        private readonly ?string $clientSpecialCode,
        private readonly string $contactPhone,
    ) {
        parent::__construct();
    }

    public function getSubdomain(): string         { return $this->subdomain; }
    public function getClientName(): string        { return $this->clientName; }
    public function getClientEmail(): string       { return $this->clientEmail; }
    public function getClientPhone(): string       { return $this->clientPhone; }
    public function getClientSpecialCode(): ?string { return $this->clientSpecialCode; }
    public function getContactPhone(): string      { return $this->contactPhone; }
}
