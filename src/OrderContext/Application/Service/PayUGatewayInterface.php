<?php declare(strict_types=1);

namespace App\OrderContext\Application\Service;

use App\OrderContext\Domain\Entity\ClientLicenseOrder;

interface PayUGatewayInterface
{
    public function makePayment(ClientLicenseOrder $order): ClientLicenseOrder;
    public function handleWebhook(string $body): ClientLicenseOrder;
    public function getPaymentMethods(): array;
}
