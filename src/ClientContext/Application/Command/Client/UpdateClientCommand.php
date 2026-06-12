<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\Client;

final class UpdateClientCommand
{
    public function __construct(private readonly int $clientId)
    {
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }
}
