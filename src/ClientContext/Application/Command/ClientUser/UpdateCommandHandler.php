<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\ClientUser;

use App\ClientContext\Domain\Repository\ClientUserRepositoryInterface;
use App\ClientContext\Domain\Exception\ClientUserNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateCommandHandler
{
    public function __construct(
        private ClientUserRepositoryInterface $repository,
    ) {
    }

    public function __invoke(UpdateCommand $command): void
    {
        $request = $command->getUpdateUserRequest();
        $user    = $this->repository->findOneByEmail($request->previousEmail);

        if (!$user) {
            throw new ClientUserNotFoundException();
        }

        $user->updateEmail($request->email);
        $this->repository->update($user);
    }
}
