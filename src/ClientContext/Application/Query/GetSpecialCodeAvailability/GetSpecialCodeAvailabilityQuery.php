<?php declare(strict_types=1);

namespace App\ClientContext\Application\Query\GetSpecialCodeAvailability;

class GetSpecialCodeAvailabilityQuery
{
    public function __construct(private readonly string $specialCode)
    {
    }

    public function getSpecialCode(): string
    {
        return $this->specialCode;
    }
}
