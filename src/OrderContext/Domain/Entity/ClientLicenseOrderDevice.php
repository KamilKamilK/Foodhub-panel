<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Entity;

use App\LicenseContext\Domain\Entity\LicenseAdditionalDevice;
use App\Shared\Domain\ValueObject\Decimal;

class ClientLicenseOrderDevice
{
    private ?int $id;
    private int $quantity;
    private Decimal $priceNet;
    private Decimal $priceGross;
    private LicenseAdditionalDevice $device;
    private ClientLicenseOrder $licenseOrder;

    public function __construct()
    {
        $this->priceNet   = new Decimal('0');
        $this->priceGross = new Decimal('0');
    }

    public static function forDevice(LicenseAdditionalDevice $device, int $quantity): self
    {
        $d = new self();
        $d->device   = $device;
        $d->quantity = $quantity;
        return $d;
    }

    public function getId(): ?int                        { return $this->id; }
    public function getQuantity(): int                   { return $this->quantity; }
    public function getPriceNet(): Decimal               { return $this->priceNet; }
    public function getPriceGross(): Decimal             { return $this->priceGross; }
    public function getDevice(): LicenseAdditionalDevice { return $this->device; }
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
