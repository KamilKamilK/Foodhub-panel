<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\ResendMail;

use App\ClientContext\Domain\Event\ConfirmationMailResent;
use App\ClientContext\Domain\Exception\ClientUserEmailNotFoundException;
use App\ClientContext\Domain\Exception\SecondMailSentException;
use App\ClientContext\Domain\Repository\ClientUserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
class ResendMailCommandHandler
{
    public function __construct(
        private ClientUserRepositoryInterface $clientUserRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(ResendMailCommand $command): void
    {
        $user = $this->clientUserRepository->findOneByEmail($command->getEmail());

        if (!$user || !$user->getConfirmationToken()) {
            throw new ClientUserEmailNotFoundException();
        }

        if ($user->isSecondMailSent()) {
            throw new SecondMailSentException();
        }

        $this->eventDispatcher->dispatch(new ConfirmationMailResent(
            email:             $command->getEmail(),
            locale:            $command->getLocale(),
            confirmationToken: $user->getConfirmationToken(),
            activationLink:    $user->getActivationLink(),
        ));

        $user->markSecondMailSent();
        $this->clientUserRepository->update($user);
    }
}
