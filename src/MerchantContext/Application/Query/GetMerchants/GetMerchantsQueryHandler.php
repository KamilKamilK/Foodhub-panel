<?php declare(strict_types=1);

namespace App\MerchantContext\Application\Query\GetMerchants;

use App\MerchantContext\Domain\Repository\MerchantRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetMerchantsQueryHandler
{
    public function __construct(
        private MerchantRepositoryInterface $merchantRepository,
    ) {
    }

    public function __invoke(GetMerchantsQuery $_query): array
    {
        return $this->merchantRepository->findAll();
    }
}
