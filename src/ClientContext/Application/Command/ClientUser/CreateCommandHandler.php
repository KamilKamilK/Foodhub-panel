<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\ClientUser;

use App\ClientContext\Domain\Repository\ClientUserRepositoryInterface;
use App\ClientContext\Application\Factory\ClientUserFactory;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateCommandHandler
{
    public function __construct(
        private ClientUserRepositoryInterface $repository,
        private ClientUserFactory $userFactory,
    ) {
    }

    public function __invoke(CreateCommand $command): void
    {
        $user = $this->userFactory->createFromApiRequest($command->getCreateUserRequest());
        $this->repository->create($user);
    }
}
