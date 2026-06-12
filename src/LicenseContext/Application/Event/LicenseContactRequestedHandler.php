<?php declare(strict_types=1);

namespace App\LicenseContext\Application\Event;

use App\LicenseContext\Domain\Event\LicenseContactRequested;
use App\MerchantContext\Domain\Repository\MerchantRepositoryInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsEventListener(event: LicenseContactRequested::class)]
class LicenseContactRequestedHandler
{
    public function __construct(
        private MerchantRepositoryInterface $merchantRepository,
        private HttpClientInterface $httpClient,
        private string $mailerSendUrl,
    ) {
    }

    public function __invoke(LicenseContactRequested $event): void
    {
        foreach ($this->merchantRepository->findDefault() as $merchant) {
            $this->httpClient->request('POST', $this->mailerSendUrl, [
                'http_version' => '1.0',
                'headers'      => ['locale' => 'pl'],
                'json'         => [
                    'project' => 'gastroonline',
                    'to'      => ['name' => $merchant->getFirstName() . ' ' . $merchant->getLastName(), 'email' => $merchant->getEmail()],
                    'message' => [
                        'identity' => 'license:contact',
                        'params'   => [
                            'contactPhone'      => $event->getContactPhone(),
                            'clientName'        => $event->getClientName(),
                            'clientEmail'       => $event->getClientEmail(),
                            'clientPhone'       => $event->getClientPhone(),
                            'clientSpecialCode' => $event->getClientSpecialCode(),
                            'clientIdentifier'  => $event->getSubdomain(),
                        ],
                    ],
                ],
            ]);
        }
    }
}
