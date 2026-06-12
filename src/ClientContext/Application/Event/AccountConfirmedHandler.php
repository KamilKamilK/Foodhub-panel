<?php declare(strict_types=1);

namespace App\ClientContext\Application\Event;

use App\ClientContext\Domain\Event\AccountConfirmed;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsEventListener(event: AccountConfirmed::class)]
class AccountConfirmedHandler
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $mailerSendUrl,
    ) {
    }

    public function __invoke(AccountConfirmed $event): void
    {
        $this->httpClient->request('POST', $this->mailerSendUrl, [
            'http_version' => '1.0',
            'headers'      => ['locale' => $event->getLocale()],
            'json'         => [
                'project' => 'gastroonline',
                'to'      => ['email' => $event->getEmail()],
                'message' => ['identity' => 'welcome'],
            ],
        ]);
    }
}
