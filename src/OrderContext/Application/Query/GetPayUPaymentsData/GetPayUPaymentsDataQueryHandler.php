<?php declare(strict_types=1);

namespace App\OrderContext\Application\Query\GetPayUPaymentsData;

use App\OrderContext\Application\DTO\PayUPaymentsDataDTO;
use App\OrderContext\Application\Service\PayUGatewayInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetPayUPaymentsDataQueryHandler
{
    public function __construct(
        private PayUGatewayInterface $payUGateway,
        private string $payuPosId,
    ) {
    }

    public function __invoke(GetPayUPaymentsDataQuery $query): PayUPaymentsDataDTO
    {
        return new PayUPaymentsDataDTO($this->payuPosId, $this->payUGateway->getPaymentMethods());
    }
}
