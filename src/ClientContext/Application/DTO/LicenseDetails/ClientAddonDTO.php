<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO\LicenseDetails;

use App\ClientContext\Domain\Entity\ClientAddon;
use App\Shared\Domain\ValueObject\Decimal;

class ClientAddonDTO
{
    public readonly int $licenseAddonId;
    public readonly string $validFrom;
    public readonly string $expirationDate;
    public readonly string $addonType;
    public readonly string $addonCategory;
    public readonly bool $isActiveOnNextPeriod;
    public readonly Decimal $priceMonth;
    public readonly Decimal $priceYear;

    public function __construct(\DateTime $validFrom, \DateTime $expirationDate, ClientAddon $clientAddon)
    {
        $this->licenseAddonId       = $clientAddon->getLicenseAddon()->getId();
        $this->validFrom            = $validFrom->format('Y-m-d');
        $this->expirationDate       = $expirationDate->format('Y-m-d');
        $this->addonType            = $clientAddon->getLicenseAddon()->getType();
        $this->addonCategory        = $clientAddon->getLicenseAddon()->getCategory();
        $this->isActiveOnNextPeriod = $clientAddon->isPlannedForNextPeriod();
        $this->priceMonth           = $clientAddon->getLicenseAddon()->getPriceMonth();
        $this->priceYear            = $clientAddon->getLicenseAddon()->getPriceYear();
    }
}
