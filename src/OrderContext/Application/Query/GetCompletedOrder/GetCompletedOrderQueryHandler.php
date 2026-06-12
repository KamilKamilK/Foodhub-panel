<?php declare(strict_types=1);

namespace App\OrderContext\Application\Query\GetCompletedOrder;

use App\OrderContext\Domain\Entity\ClientLicenseOrder;
use App\OrderContext\Domain\Exception\OrderNotFoundException;
use App\OrderContext\Domain\Repository\ClientLicenseOrderRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetCompletedOrderQueryHandler
{
    public function __construct(
        private readonly ClientLicenseOrderRepositoryInterface $clientLicenseOrderRepository,
    ) {
    }

    public function __invoke(GetCompletedOrderQuery $query): ClientLicenseOrder
    {
        $order = $this->clientLicenseOrderRepository->findOneCompletedByOrderId($query->getOrderId());
        if ($order === null) {
            throw new OrderNotFoundException();
        }

        return $order;
    }
}
