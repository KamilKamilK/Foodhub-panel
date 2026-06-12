<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\Client;

use App\ClientContext\Domain\Repository\ClientRepositoryInterface;
use App\ClientContext\Domain\Exception\ClientNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateClientCommandHandler
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {
    }

    public function __invoke(UpdateClientCommand $command): void
    {
        $client = $this->clientRepository->findById($command->getClientId());

        if (!$client) {
            throw new ClientNotFoundException();
        }

        $client->getLatestClientLicense()->syncAddonsAndDevicesExpiry();

        $this->clientRepository->update($client);
    }
}
