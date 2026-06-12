<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\Setup;

use App\ClientContext\Application\Command\Setup\DTO\Db;
use App\ClientContext\Application\Factory\ClientFactory;
use App\ClientContext\Application\Factory\ClientUserFactory;
use App\ClientContext\Application\Service\ConfirmationUrlBuilderService;
use App\ClientContext\Application\Service\TenantIdentifierGeneratorInterface;
use App\ClientContext\Domain\Event\ClientRegistered;
use App\ClientContext\Domain\Exception\ClientUserNotUniqueEmailException;
use App\Shared\Domain\ValueObject\Email;
use App\ClientContext\Domain\Repository\ClientRepositoryInterface;
use App\ClientContext\Domain\Repository\ClientUserRepositoryInterface;
use App\ClientContext\Application\Service\DatabaseProvisioningServiceInterface;
use App\ClientContext\Application\Service\TenantApiClientInterface;
use App\Shared\Application\Service\EnvStorageInterface;
use App\Shared\Application\Service\TransactionServiceInterface;
use App\Shared\Domain\Exception\AppException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
class SetupCommandHandler
{
    public function __construct(
        private ClientUserRepositoryInterface $clientUserRepository,
        private ClientRepositoryInterface $clientRepository,
        private DatabaseProvisioningServiceInterface $databaseService,
        private EnvStorageInterface $envStorage,
        private TenantIdentifierGeneratorInterface $identifierGenerator,
        private ConfirmationUrlBuilderService $confirmationUrlBuilder,
        private string $envStorageDir,
        private TransactionServiceInterface $transactionService,
        private ClientFactory $clientFactory,
        private ClientUserFactory $clientUserFactory,
        private TenantApiClientInterface $tenantApiClient,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(SetupCommand $command): void
    {
        $email = Email::fromString($command->getUser()->getEmail());

        if ($this->clientUserRepository->findOneByEmail($email->getValue())) {
            throw new ClientUserNotUniqueEmailException();
        }

        $dbName = $this->identifierGenerator->generateUniqueIdentifier(
            fn(string $id) => (bool) $this->clientRepository->findOneBySubdomain($id)
        );
        $db = new Db($dbName, $dbName, $this->identifierGenerator->generateIdentifier());
        $token  = $this->identifierGenerator->generateActivationToken();

        $this->provisionDatabase($db);

        $client = $this->clientFactory->createFromSetupRequest($command, $db);
        $user   = $this->clientUserFactory->createFromSetupRequest($command, $token, $client);

        try {
            $this->transactionService->beginTransaction();

            $this->saveEnv($db);

            $authToken = $this->tenantApiClient->install(
                $command->toArray(),
                $db->getName(),
                $command->getLanguage(),
            );
            $user->storeApiAuthToken($authToken);

            $this->clientRepository->create($client);
            $this->clientUserRepository->create($user);

            $this->transactionService->commit();
        } catch (AppException $e) {
            $this->transactionService->rollback();
            throw $e;
        }

        $user->storeActivationLink(
            $this->confirmationUrlBuilder->buildConfirmationUrl($email->getValue(), $authToken)
        );
        $this->clientUserRepository->update($user);

        $this->eventDispatcher->dispatch(new ClientRegistered(
            subdomain:         $db->getName(),
            email:             $email->getValue(),
            userName:          $command->getUser()->getName(),
            language:          $command->getLanguage(),
            confirmationToken: $token,
        ));

    }

    private function provisionDatabase(Db $db): void
    {
        $this->databaseService->createDatabase($db->getName());
        $this->databaseService->createUser($db->getUser(), $db->getPassword());
        $this->databaseService->grantPrivileges($db->getName(), $db->getUser());
    }

    private function saveEnv(Db $db): void
    {
        $this->envStorage->writeEnv(
            $this->envStorageDir . DIRECTORY_SEPARATOR . $db->getName() . DIRECTORY_SEPARATOR . '.env',
            [
                'DB_NAME'     => $db->getName(),
                'DB_USER'     => $db->getUser(),
                'DB_PASSWORD' => $db->getPassword(),
            ],
        );
    }
}
