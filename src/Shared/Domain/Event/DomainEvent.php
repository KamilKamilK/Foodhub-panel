<?php declare(strict_types=1);

namespace App\Shared\Domain\Event;

abstract class DomainEvent
{
    private readonly \DateTimeImmutable $occurredOn;

    public function __construct()
    {
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
