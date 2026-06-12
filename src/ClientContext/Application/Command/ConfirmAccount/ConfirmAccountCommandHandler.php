<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\ConfirmAccount;

use App\ClientContext\Domain\Event\AccountConfirmed;
use App\ClientContext\Domain\Exception\ClientUserWithTokenNotFoundException;
use App\ClientContext\Domain\Repository\ClientUserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
class ConfirmAccountCommandHandler
{
    public function __construct(
        private ClientUserRepositoryInterface $clientUserRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(ConfirmAccountCommand $command): void
    {
        if (!$command->getConfirmationToken()) {
            throw new ClientUserWithTokenNotFoundException();
        }

        $user = $this->clientUserRepository->findOneByConfirmationToken($command->getConfirmationToken());

        if (!$user) {
            throw new ClientUserWithTokenNotFoundException();
        }

        $user->confirm();

        $this->clientUserRepository->update($user);

        $this->eventDispatcher->dispatch(new AccountConfirmed(
            email:  $user->getEmail(),
            locale: $command->getLocale(),
        ));
    }
}
