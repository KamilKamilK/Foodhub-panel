<?php declare(strict_types=1);

namespace App\OrderContext\Application\Query\GetOrder;

use App\OrderContext\Domain\Entity\ClientLicenseOrder;
use App\OrderContext\Domain\Exception\OrderNotFoundException;
use App\OrderContext\Domain\Repository\ClientLicenseOrderRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetOrderQueryHandler
{
    public function __construct(
        private ClientLicenseOrderRepositoryInterface $clientLicenseOrderRepository,
    ) {
    }

    public function __invoke(GetOrderQuery $query): ClientLicenseOrder
    {
        $order = $this->clientLicenseOrderRepository->findOneByOrderId($query->getOrderId());
        if (!$order) {
            throw new OrderNotFoundException();
        }

        return $order;
    }
}
