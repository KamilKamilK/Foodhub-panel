<?php declare(strict_types=1);

namespace App\ClientContext\Application\Query\GetUrlByEmail;

use App\ClientContext\Domain\Repository\ClientUserRepositoryInterface;
use App\ClientContext\Domain\Exception\ClientUserEmailNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetUrlByEmailQueryHandler
{
    public function __construct(
        private ClientUserRepositoryInterface $clientUserRepository,
        private string $apiProtocol,
        private string $apiDomain,
        private string $appEnv,
    ) {
    }

    public function __invoke(GetUrlByEmailQuery $query): array
    {
        $user = $this->clientUserRepository->findOneByEmail($query->getEmail());

        if (!$user) {
            throw new ClientUserEmailNotFoundException();
        }

        if ($this->appEnv === 'dev' && $user->getClient()->getSubdomain() === 'localhost') {
            return [
                'url'            => sprintf('%s://%s', $this->apiProtocol, $this->apiDomain),
                'active'         => $user->isActive(),
                'secondMailSent' => $user->isSecondMailSent(),
            ];
        }

        return [
            'url'            => $this->apiProtocol . '://' . $user->getClient()->getSubdomain() . '.' . $this->apiDomain,
            'active'         => $user->isActive(),
            'secondMailSent' => $user->isSecondMailSent(),
        ];
    }
}
