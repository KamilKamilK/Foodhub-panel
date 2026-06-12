<?php declare(strict_types=1);

namespace App\ClientContext\Application\Event;

use App\ClientContext\Domain\Event\ConfirmationMailResent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsEventListener(event: ConfirmationMailResent::class)]
class ConfirmationMailResentHandler
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $mailerSendUrl,
        private string $landingPageConfirmationUrl,
    ) {
    }

    public function __invoke(ConfirmationMailResent $event): void
    {
        $this->httpClient->request('POST', $this->mailerSendUrl, [
            'http_version' => '1.0',
            'headers'      => ['locale' => $event->getLocale()],
            'json'         => [
                'project' => 'gastroonline',
                'to'      => ['email' => $event->getEmail()],
                'message' => [
                    'identity' => 'setup:confirmation',
                    'params'   => [
                        'url'               => $this->landingPageConfirmationUrl,
                        'confirmationToken' => $event->getConfirmationToken(),
                        'resultUrl'         => urlencode($event->getActivationLink()),
                    ],
                ],
            ],
        ]);
    }
}
