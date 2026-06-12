<?php declare(strict_types=1);

namespace App\ClientContext\Application\Query\GetClientsList;

use App\ClientContext\Domain\Repository\ClientRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetClientsListQueryHandler
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {
    }

    public function __invoke(GetClientsListQuery $_query): array
    {
        return $this->clientRepository->findAllWithDependencies();
    }
}
