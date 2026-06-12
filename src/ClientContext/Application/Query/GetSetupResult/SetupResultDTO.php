<?php declare(strict_types=1);

namespace App\ClientContext\Application\Query\GetSetupResult;

final class SetupResultDTO
{
    public function __construct(
        public readonly string $apiUrl,
        public readonly string $authToken,
        public readonly string $confirmationUrl,
    ) {
    }

    public function toArray(): array
    {
        return ['url' => $this->apiUrl, 'authToken' => $this->authToken];
    }

    public function getConfirmationUrl(): string
    {
        return $this->confirmationUrl;
    }
}
