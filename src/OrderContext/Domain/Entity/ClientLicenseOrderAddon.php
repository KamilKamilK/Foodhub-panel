<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Entity;

use App\LicenseContext\Domain\Entity\LicenseAddon;
use App\Shared\Domain\ValueObject\Decimal;

class ClientLicenseOrderAddon
{
    private ?int $id;
    private Decimal $priceNet;
    private Decimal $priceGross;
    private LicenseAddon $addon;
    private ClientLicenseOrder $licenseOrder;

    public function __construct()
    {
        $this->priceNet   = new Decimal('0');
        $this->priceGross = new Decimal('0');
    }

    public static function forAddon(LicenseAddon $addon): self
    {
        $a = new self();
        $a->addon = $addon;
        return $a;
    }

    public function getId(): ?int            { return $this->id; }
    public function getPriceNet(): Decimal   { return $this->priceNet; }
    public function getPriceGross(): Decimal { return $this->priceGross; }
    public function getAddon(): LicenseAddon { return $this->addon; }
    public function getLicenseOrder(): ClientLicenseOrder { return $this->licenseOrder; }

    public function setLicenseOrder(ClientLicenseOrder $licenseOrder): self
    {
        $this->licenseOrder = $licenseOrder;
        return $this;
    }

    public function applyPrice(Decimal $priceNet, Decimal $priceGross): void
    {
        $this->priceNet   = $priceNet;
        $this->priceGross = $priceGross;
    }
}
