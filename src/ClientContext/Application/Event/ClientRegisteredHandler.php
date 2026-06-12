<?php declare(strict_types=1);

namespace App\ClientContext\Application\Event;

use App\ClientContext\Domain\Event\ClientRegistered;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsEventListener(event: ClientRegistered::class)]
class ClientRegisteredHandler
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $mailerSendUrl,
        private string $landingPageConfirmationUrl,
    ) {
    }

    public function __invoke(ClientRegistered $event): void
    {
        $this->httpClient->request('POST', $this->mailerSendUrl, [
            'http_version' => '1.0',
            'headers'      => ['locale' => $event->getLanguage()],
            'json'         => [
                'project' => 'gastroonline',
                'to'      => ['name' => $event->getUserName(), 'email' => $event->getEmail()],
                'message' => [
                    'identity' => 'setup:confirmation',
                    'params'   => [
                        'url'               => $this->landingPageConfirmationUrl,
                        'confirmationToken' => $event->getConfirmationToken(),
                    ],
                ],
            ],
        ]);
    }
}
