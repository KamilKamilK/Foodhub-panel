<?php declare(strict_types=1);

namespace App\ClientContext\Application\Factory;

use App\ClientContext\Application\Command\Setup\SetupCommand;
use App\ClientContext\Application\DTO\CreateUserRequest;
use App\ClientContext\Domain\Entity\Client;
use App\ClientContext\Domain\Repository\ClientRepositoryInterface;
use App\ClientContext\Domain\Entity\ClientUser;
use App\ClientContext\Domain\Exception\ClientNotFoundException;

class ClientUserFactory
{
    public function __construct(private ClientRepositoryInterface $clientRepository)
    {
    }

    public function createFromApiRequest(CreateUserRequest $createUserRequest): ClientUser
    {
        $client = $this->clientRepository->findOneBySubdomain($createUserRequest->subDomain);
        if (!$client) {
            throw new ClientNotFoundException();
        }

        return ClientUser::forAdminCreation(
            email:  $createUserRequest->email,
            client: $client,
            active: $createUserRequest->active,
        );
    }

    public function createFromSetupRequest(SetupCommand $command, string $confirmationToken, Client $client): ClientUser
    {
        return ClientUser::forRegistration(
            email:             $command->getUser()->getEmail(),
            specialCode:       $command->getUser()->getSpecialCode(),
            confirmationToken: $confirmationToken,
            client:            $client,
        );
    }
}
