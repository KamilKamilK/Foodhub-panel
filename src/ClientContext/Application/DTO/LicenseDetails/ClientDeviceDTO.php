<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO\LicenseDetails;

use App\ClientContext\Domain\Entity\ClientAdditionalDevice;
use App\Shared\Domain\ValueObject\Decimal;

class ClientDeviceDTO
{
    public readonly int $licenseDeviceId;
    public readonly string $validFrom;
    public readonly string $expirationDate;
    public readonly string $deviceType;
    public readonly bool $isActiveOnNextPeriod;
    public readonly Decimal $priceMonth;
    public readonly Decimal $priceYear;

    public function __construct(ClientAdditionalDevice $clientAdditionalDevice)
    {
        $this->licenseDeviceId      = $clientAdditionalDevice->getLicenseAdditionalDevice()->getId();
        $this->validFrom            = $clientAdditionalDevice->getValidFrom()->format('Y-m-d');
        $this->expirationDate       = $clientAdditionalDevice->getExpiredAt()->format('Y-m-d');
        $this->deviceType           = $clientAdditionalDevice->getLicenseAdditionalDevice()->getType();
        $this->isActiveOnNextPeriod = $clientAdditionalDevice->isPlannedForNextPeriod();
        $this->priceMonth           = $clientAdditionalDevice->getLicenseAdditionalDevice()->getPriceMonth();
        $this->priceYear            = $clientAdditionalDevice->getLicenseAdditionalDevice()->getPriceYear();
    }
}
