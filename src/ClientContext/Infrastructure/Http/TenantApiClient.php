<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Http;

use App\ClientContext\Application\Service\TenantApiClientInterface;
use App\ClientContext\Domain\Exception\InstallationFailedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TenantApiClient implements TenantApiClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $apiProtocol,
        private string $apiDomain,
        private string $apiSetupPath,
    ) {
    }

    public function install(array $payload, string $subdomain, string $language): string
    {
        $response = $this->httpClient->request('POST', sprintf(
            '%s://%s.%s%s',
            $this->apiProtocol,
            $subdomain,
            $this->apiDomain,
            $this->apiSetupPath,
        ), [
            'http_version' => '1.0',
            'headers'      => ['locale' => $language],
            'json'         => $payload,
        ]);

        if ($response->getStatusCode() !== Response::HTTP_CREATED) {
            throw new InstallationFailedException(json_decode($response->getContent(false), true) ?? []);
        }

        return json_decode($response->getContent(false))->authToken;
    }
}
