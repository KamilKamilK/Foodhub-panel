<?php declare(strict_types=1);

namespace App\LicenseContext\Application\Command\LicenseContactCommand;

use App\ClientContext\Domain\Exception\ClientNotFoundException;
use App\ClientContext\Domain\Repository\ClientRepositoryInterface;
use App\LicenseContext\Domain\Event\LicenseContactRequested;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
class LicenseContactCommandHandler
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(LicenseContactCommand $command): void
    {
        $client = $this->clientRepository->findOneBySubdomain($command->getSubdomain()->getValue());
        if (!$client) {
            throw new ClientNotFoundException();
        }

        $this->eventDispatcher->dispatch(new LicenseContactRequested(
            subdomain:         $client->getSubdomain(),
            clientName:        $client->getFullName(),
            clientEmail:       $client->getEmail(),
            clientPhone:       $client->getPhone(),
            clientSpecialCode: $client->getRegSpecialCode(),
            contactPhone:      $command->getContactPhone(),
        ));
    }
}
