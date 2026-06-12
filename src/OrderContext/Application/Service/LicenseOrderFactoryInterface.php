<?php declare(strict_types=1);

namespace App\OrderContext\Application\Service;

use App\OrderContext\Application\DTO\LicenseOrder\LicenseOrderDTO;
use App\OrderContext\Application\DTO\LicenseOrder\OrderAddonsDTO;
use App\OrderContext\Domain\Entity\ClientLicenseOrder;

interface LicenseOrderFactoryInterface
{
    public function createFromOrderRequest(LicenseOrderDTO $request, string $orderType): ClientLicenseOrder;
    public function createFromOrderAddonsRequest(OrderAddonsDTO $request): ClientLicenseOrder;
}
