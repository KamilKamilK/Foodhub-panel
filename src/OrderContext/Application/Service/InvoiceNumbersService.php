<?php declare(strict_types=1);

namespace App\OrderContext\Application\Service;

use App\OrderContext\Domain\Repository\ClientLicenseOrderRepositoryInterface;
use App\OrderContext\Domain\Entity\ClientLicenseOrder;
class InvoiceNumbersService
{
    private string $pattern = "FV {{year}}/LK/{{documentNo}}";

    public function __construct(
        private ClientLicenseOrderRepositoryInterface $clientLicenseOrderRepository,
    ) {
    }

    public function getInvoiceNumber(): string
    {
        $pattern = str_replace("{{year}}", date('Y'), $this->pattern);
        $number  = $this->getCounterForSaleDocument($pattern);

        return str_replace("{{documentNo}}", sprintf('%05d', $number), $pattern);
    }

    private function getCounterForSaleDocument(string $pattern): int
    {
        $tempNumber      = str_replace("{{documentNo}}", "%", $pattern);
        $startNumberPos  = strpos($tempNumber, "%");
        $startNumberSuffix = substr($tempNumber, $startNumberPos + 1);

        $salesDocuments = $this->clientLicenseOrderRepository->findOrdersByDocumentNumberPattern($tempNumber);

        $numbers = array_map(
            fn(ClientLicenseOrder $o) => (int) str_replace($startNumberSuffix, '', substr($o->getInvoiceNo(), $startNumberPos)),
            $salesDocuments,
        );

        return count($numbers) >= 1 ? max($numbers) + 1 : 1;
    }
}
