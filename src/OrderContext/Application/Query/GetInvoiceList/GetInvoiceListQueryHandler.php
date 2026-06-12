<?php declare(strict_types=1);

namespace App\OrderContext\Application\Query\GetInvoiceList;

use App\ClientContext\Domain\Repository\ClientRepositoryInterface;
use App\ClientContext\Domain\Exception\ClientNotFoundException;
use App\OrderContext\Domain\Repository\ClientLicenseOrderRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetInvoiceListQueryHandler
{
    public function __construct(
        private ClientLicenseOrderRepositoryInterface $clientLicenseOrderRepository,
        private ClientRepositoryInterface $clientRepository,
    ) {
    }

    public function __invoke(GetInvoiceListQuery $query): array
    {
        $client = $this->clientRepository->findOneBySubdomain($query->getSubdomain()->getValue());
        if (!$client) {
            throw new ClientNotFoundException();
        }

        return $this->clientLicenseOrderRepository->findInvoices($query->getSubdomain()->getValue());
    }
}
