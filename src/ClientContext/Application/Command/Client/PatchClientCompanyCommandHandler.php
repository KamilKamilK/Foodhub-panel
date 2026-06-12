<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\Client;

use App\ClientContext\Domain\Repository\ClientRepositoryInterface;
use App\ClientContext\Domain\Exception\ClientNotFoundException;
use App\ClientContext\Application\Factory\AddressFactory;
use App\ClientContext\Application\Factory\CompanyFactory;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PatchClientCompanyCommandHandler
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private CompanyFactory $companyFactory,
        private AddressFactory $addressFactory,
    ) {
    }

    public function __invoke(PatchClientCompanyCommand $command): void
    {
        $client = $this->clientRepository->findOneBySubdomain($command->getSubDomain()->getValue());
        if (!$client) {
            throw new ClientNotFoundException();
        }

        $client->setCompany($this->companyFactory->create($command->getCompanyData()));
        $client->setAddress($this->addressFactory->create($command->getAddressData()));

        $this->clientRepository->update($client);
    }
}
