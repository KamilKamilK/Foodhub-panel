<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO\LicenseDetails;

use App\ClientContext\Domain\Entity\ClientAdditionalDevice;
use App\ClientContext\Domain\Entity\ClientAddon;
use App\ClientContext\Domain\Entity\ClientLicense;
use App\Shared\Domain\ValueObject\Decimal;

final class ClientLicenseDTO
{
    public readonly int $licenseId;
    public readonly string $name;
    public readonly string $validFrom;
    public readonly string $expirationDate;
    public readonly string $period;
    public readonly bool $isTrial;
    public readonly bool $includedFoodHubOrder;
    /** @var ClientDeviceDTO[] */
    public readonly array $additionalDevices;
    /** @var ClientAddonDTO[] */
    public readonly array $addons;
    public readonly Decimal $priceMonth;
    public readonly Decimal $priceYear;

    public function __construct(ClientLicense $clientLicense, \DateTime $validFrom, \DateTime $expiredAt, string $locale)
    {
        $this->licenseId            = $clientLicense->getLicense()->getId();
        $this->name                 = $clientLicense->getLicense()->getTranslation($locale)->getName();
        $this->validFrom            = $validFrom->format('Y-m-d');
        $this->expirationDate       = $expiredAt->format('Y-m-d');
        $this->period               = $clientLicense->getPeriod();
        $this->isTrial              = $clientLicense->getLicense()->isTrial();
        $this->includedFoodHubOrder = $clientLicense->getLicense()->getIncludedFoodHubOrder();

        $this->additionalDevices = $clientLicense->getAdditionalDevices()
            ->map(fn (ClientAdditionalDevice $d) => new ClientDeviceDTO($d))
            ->toArray();

        $this->addons = $clientLicense->getAddons()
            ->map(fn (ClientAddon $a) => new ClientAddonDTO($a->getValidFrom(), $a->getExpiredAt(), $a))
            ->toArray();

        $this->priceMonth = $clientLicense->getLicense()->getPriceMonth();
        $this->priceYear  = $clientLicense->getLicense()->getPriceYear();
    }
}
