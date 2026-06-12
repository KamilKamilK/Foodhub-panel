<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

final class LicenseOrdered extends DomainEvent
{
    public function __construct(
        private readonly string $orderId,
        private readonly string $subdomain,
        private readonly int $licenseId,
        private readonly string $period,
    ) {
        parent::__construct();
    }

    public function getOrderId(): string    { return $this->orderId; }
    public function getSubdomain(): string  { return $this->subdomain; }
    public function getLicenseId(): int     { return $this->licenseId; }
    public function getPeriod(): string     { return $this->period; }
}
