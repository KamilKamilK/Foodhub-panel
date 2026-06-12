<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Repository;

use App\OrderContext\Domain\Entity\ClientLicenseOrder;

interface ClientLicenseOrderRepositoryInterface
{
    public function create(ClientLicenseOrder $clientLicenseOrder): void;

    public function update(ClientLicenseOrder $clientLicenseOrder): void;

    public function findOneByOrderId(string $orderId): ?ClientLicenseOrder;

    public function findOneCompletedByOrderId(string $orderId): ?ClientLicenseOrder;

    /**
     * @return ClientLicenseOrder[]
     */
    public function findOrdersByDocumentNumberPattern(string $numberPattern): array;

    /**
     * @return ClientLicenseOrder[]
     */
    public function findInvoices(string $subdomain): array;
}