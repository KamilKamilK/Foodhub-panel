<?php declare(strict_types=1);

namespace App\ClientContext\Application\Query\GetSetupResult;

use App\ClientContext\Application\Service\ConfirmationUrlBuilderService;
use App\ClientContext\Domain\Exception\ClientUserEmailNotFoundException;
use App\ClientContext\Domain\Repository\ClientUserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetSetupResultQueryHandler
{
    public function __construct(
        private readonly ClientUserRepositoryInterface $clientUserRepository,
        private readonly ConfirmationUrlBuilderService $confirmationUrlBuilder,
    ) {
    }

    public function __invoke(GetSetupResultQuery $query): SetupResultDTO
    {
        $user = $this->clientUserRepository->findOneByEmail($query->getEmail());
        if (!$user) {
            throw new ClientUserEmailNotFoundException();
        }

        $authToken = $user->getApiAuthToken();
        if ($authToken === null) {
            throw new ClientUserEmailNotFoundException();
        }

        $subdomain = $user->getClient()?->getSubdomain() ?? '';

        return new SetupResultDTO(
            apiUrl:          $this->confirmationUrlBuilder->buildApiUrl($subdomain),
            authToken:       $authToken,
            confirmationUrl: $this->confirmationUrlBuilder->buildConfirmationUrl($query->getEmail(), $authToken),
        );
    }
}
