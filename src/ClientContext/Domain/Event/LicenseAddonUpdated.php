<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

final class LicenseAddonUpdated extends DomainEvent
{
    public function __construct(
        private readonly string $subdomain,
        private readonly int $clientLicenseId,
    ) {
        parent::__construct();
    }

    public function getSubdomain(): string      { return $this->subdomain; }
    public function getClientLicenseId(): int   { return $this->clientLicenseId; }
}
