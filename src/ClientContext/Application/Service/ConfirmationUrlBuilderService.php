<?php declare(strict_types=1);

namespace App\ClientContext\Application\Service;

final class ConfirmationUrlBuilderService
{
    public function __construct(
        private readonly string $appAuthLoginUrl,
        private readonly string $apiProtocol,
        private readonly string $apiDomain,
    ) {
    }

    public function buildConfirmationUrl(string $email, string $authToken): string
    {
        return sprintf('%s?email=%s&auth-token=%s', $this->appAuthLoginUrl, $email, $authToken);
    }

    public function buildApiUrl(string $subdomain): string
    {
        return $this->apiProtocol . '://' . $subdomain . '.' . $this->apiDomain;
    }
}
