<?php declare(strict_types=1);

namespace App\ClientContext\Application\Query\GetSpecialCodeAvailability;

use App\MerchantContext\Application\DTO\SpecialCodeAvailabilityDTO;
use App\MerchantContext\Domain\Repository\MerchantRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetSpecialCodeAvailabilityQueryHandler
{
    public function __construct(
        private MerchantRepositoryInterface $merchantRepository,
    ) {
    }

    public function __invoke(GetSpecialCodeAvailabilityQuery $query): SpecialCodeAvailabilityDTO
    {
        $merchant = $this->merchantRepository->findOneBySpecialCode($query->getSpecialCode());

        return new SpecialCodeAvailabilityDTO(!!$merchant);
    }
}
