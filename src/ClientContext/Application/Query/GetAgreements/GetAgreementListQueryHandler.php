<?php declare(strict_types=1);

namespace App\ClientContext\Application\Query\GetAgreements;

use App\ClientContext\Domain\Repository\AgreementRepositoryInterface;
use App\ClientContext\Domain\Exception\AgreementNotFoundException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetAgreementListQueryHandler
{
    public function __construct(
        private AgreementRepositoryInterface $agreementRepository,
    ) {
    }

    public function __invoke(GetAgreementListQuery $query): array
    {
        $agreements = $this->agreementRepository->findByLocale($query->getLocale());

        if (!$agreements) {
            throw new AgreementNotFoundException();
        }

        return $agreements;
    }
}
