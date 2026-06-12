<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Entity;

use App\ClientContext\Domain\Entity\Client;
use App\ClientContext\Domain\Entity\ClientLicense;
use App\LicenseContext\Domain\Entity\License;
use App\LicenseContext\Domain\Entity\LicenseSet;
use App\OrderContext\Domain\ValueObject\AddonsPricingBreakdown;
use App\OrderContext\Domain\ValueObject\BuyerData;
use App\OrderContext\Domain\ValueObject\PricingBreakdown;
use App\Shared\Domain\ValueObject\Decimal;
use Gedmo\Timestampable\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ClientLicenseOrder
{
    use Timestampable;

    private ?int $id;

    /**
     * @var string
     */
    private string $orderId;

    /**
     * @var string|null
     */
    private ?string $specialCode = null;

    private string $period;

    /**
     * @var string
     */
    private string $subdomain;

    private BuyerData $buyerData;

    private Decimal $priceNet;
    private Decimal $priceGross;
    private Decimal $extraPriceNet;
    private Decimal $extraPriceGross;
    private Decimal $addonsExtraPriceNet;
    private Decimal $addonsExtraPriceGross;
    private Decimal $devicesExtraPriceNet;
    private Decimal $devicesExtraPriceGross;

    private string $orderType;

    /**
     * @var string
     */
    private ?string $invoiceNo = null;

    /**
     * @var ?License
     */
    private ?License $license = null;

    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var ?ClientLicense
     */
    private ?ClientLicense $clientLicense = null;

    private ?LicenseSet $selectedSet = null;

    /**
     * @var Payment
     */
    private Payment $payment;

    /**
     * @var Collection|array<ClientLicenseOrderAddon>
     */
    private Collection $selectedAddons;

    /**
     * @var Collection|array<ClientLicenseOrderDevice>
     */
    private Collection $additionalDevices;

    public function __construct(
        string $orderId,
        Client $client,
        string $subdomain,
        ?string $specialCode,
        ?License $license,
        ?LicenseSet $selectedSet,
        string $period,
        BuyerData $buyerData,
        string $orderType,
    ) {
        $this->orderId           = $orderId;
        $this->client            = $client;
        $this->subdomain         = $subdomain;
        $this->specialCode       = $specialCode;
        $this->license           = $license;
        $this->selectedSet       = $selectedSet;
        $this->period            = $period;
        $this->buyerData         = $buyerData;
        $this->orderType         = $orderType;
        $this->selectedAddons    = new ArrayCollection();
        $this->additionalDevices = new ArrayCollection();
        $this->priceNet              = new Decimal('0');
        $this->priceGross            = new Decimal('0');
        $this->extraPriceNet         = new Decimal('0');
        $this->extraPriceGross       = new Decimal('0');
        $this->addonsExtraPriceNet   = new Decimal('0');
        $this->addonsExtraPriceGross = new Decimal('0');
        $this->devicesExtraPriceNet  = new Decimal('0');
        $this->devicesExtraPriceGross = new Decimal('0');
    }

    public static function generateOrderId(): string
    {
        return sprintf(
            '%04x-%04x-%04x-%04x',
            random_int(0, 65535),
            random_int(0, 65535),
            random_int(0, 65535),
            random_int(16384, 20479),
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getSpecialCode(): ?string  { return $this->specialCode; }
    public function getPeriod(): string        { return $this->period; }
    public function getSubdomain(): string     { return $this->subdomain; }
    public function getBuyerData(): BuyerData  { return $this->buyerData; }

    public function getBuyerName(): string      { return $this->buyerData->name; }
    public function getBuyerVatNumber(): string { return $this->buyerData->vatNumber; }
    public function getBuyerStreet(): string    { return $this->buyerData->street; }
    public function getBuyerHouse(): string     { return $this->buyerData->house; }
    public function getBuyerFlat(): ?string     { return $this->buyerData->flat; }
    public function getBuyerCity(): string      { return $this->buyerData->city; }
    public function getBuyerZip(): string       { return $this->buyerData->zip; }

    public function getPriceNet(): Decimal            { return $this->priceNet; }
    public function getPriceGross(): Decimal          { return $this->priceGross; }
    public function getExtraPriceNet(): Decimal       { return $this->extraPriceNet; }
    public function getExtraPriceGross(): Decimal     { return $this->extraPriceGross; }
    public function getAddonsExtraPriceNet(): Decimal  { return $this->addonsExtraPriceNet; }
    public function getAddonsExtraPriceGross(): Decimal { return $this->addonsExtraPriceGross; }
    public function getDevicesExtraPriceNet(): Decimal  { return $this->devicesExtraPriceNet; }
    public function getDevicesExtraPriceGross(): Decimal { return $this->devicesExtraPriceGross; }

    public function applyPricing(PricingBreakdown $breakdown): void
    {
        $this->priceNet               = $breakdown->priceNet;
        $this->priceGross             = $breakdown->priceGross;
        $this->extraPriceNet          = $breakdown->extraPriceNet;
        $this->extraPriceGross        = $breakdown->extraPriceGross;
        $this->addonsExtraPriceNet    = $breakdown->addonsExtraPriceNet;
        $this->addonsExtraPriceGross  = $breakdown->addonsExtraPriceGross;
        $this->devicesExtraPriceNet   = $breakdown->devicesExtraPriceNet;
        $this->devicesExtraPriceGross = $breakdown->devicesExtraPriceGross;
    }

    public function applyAddonsPricing(AddonsPricingBreakdown $breakdown): void
    {
        $this->addonsExtraPriceNet    = $breakdown->addonsExtraPriceNet;
        $this->addonsExtraPriceGross  = $breakdown->addonsExtraPriceGross;
        $this->devicesExtraPriceNet   = $breakdown->devicesExtraPriceNet;
        $this->devicesExtraPriceGross = $breakdown->devicesExtraPriceGross;
    }

    public function getOrderType(): string     { return $this->orderType; }
    public function getInvoiceNo(): ?string    { return $this->invoiceNo; }
    public function getLicense(): ?License     { return $this->license; }
    public function getClient(): Client        { return $this->client; }

    public function getClientLicense(): ?ClientLicense
    {
        return $this->clientLicense;
    }

    public function getSelectedAddons(): Collection
    {
        return $this->selectedAddons;
    }

    public function addSelectedAddon(ClientLicenseOrderAddon $selectedAddon): self
    {
        if (!$this->selectedAddons->contains($selectedAddon)) {
            $this->selectedAddons->add($selectedAddon);
            $selectedAddon->setLicenseOrder($this);
        }

        return $this;
    }

    public function removeSelectedAddon(ClientLicenseOrderAddon $selectedAddon): self
    {
        if ($this->selectedAddons->contains($selectedAddon)) {
            $this->selectedAddons->removeElement($selectedAddon);
        }

        return $this;
    }

    public function getAdditionalDevices(): Collection
    {
        return $this->additionalDevices;
    }

    public function addAdditionalDevice(ClientLicenseOrderDevice $additionalDevice): self
    {
        if (!$this->additionalDevices->contains($additionalDevice)) {
            $this->additionalDevices->add($additionalDevice);
            $additionalDevice->setLicenseOrder($this);
        }

        return $this;
    }

    public function removeAdditionalDevice(ClientLicenseOrderDevice $additionalDevice): self
    {
        if ($this->additionalDevices->contains($additionalDevice)) {
            $this->additionalDevices->removeElement($additionalDevice);
        }

        return $this;
    }

        public function getSelectedSet(): ?LicenseSet { return $this->selectedSet; }

        public function getPayment(): Payment { return $this->payment; }

    public function attachPayment(Payment $payment): void
    {
        $this->payment = $payment;
    }

    public function getLicenseTotalPriceNet(): Decimal
    {
        return $this->getPriceNet()->add($this->extraPriceNet);
    }

    public function getLicenseTotalPriceGross(): Decimal
    {
        return $this->getPriceGross()->add($this->extraPriceGross);
    }

    public function getLicenseTotalTax(): Decimal
    {
        return $this->getLicenseTotalPriceGross()->sub($this->getLicenseTotalPriceNet());
    }

    public function getServicesTotalPriceNet(): Decimal
    {
        $total = new Decimal("0");

        /** @var ClientLicenseOrderAddon $addon */
        foreach ($this->getSelectedAddons() as $addon) {
            $total = $total->add($addon->getPriceNet());
        }

        $total = $total->add($this->addonsExtraPriceNet);

        /** @var ClientLicenseOrderDevice $device */
        foreach ($this->getAdditionalDevices() as $device) {
            $total = $total->add($device->getPriceNet());
        }

        $total = $total->add($this->devicesExtraPriceNet);

        return $total;
    }

    public function getServicesTotalPriceGross(): Decimal
    {
        $total = new Decimal("0");

        /** @var ClientLicenseOrderAddon $addon */
        foreach ($this->getSelectedAddons() as $addon) {
            $total = $total->add($addon->getPriceGross());
        }

        $total = $total->add($this->addonsExtraPriceGross);

        /** @var ClientLicenseOrderDevice $device */
        foreach ($this->getAdditionalDevices() as $device) {
            $total = $total->add($device->getPriceGross());
        }

        $total = $total->add($this->devicesExtraPriceGross);

        return $total;
    }

    public function getServicesTotalTax(): Decimal
    {
        return $this->getServicesTotalPriceGross()->sub($this->getServicesTotalPriceNet());
    }

    public function assignLicense(ClientLicense $license): void
    {
        $this->clientLicense = $license;
    }

    public function markInvoiced(string $invoiceNo): void
    {
        $this->invoiceNo = $invoiceNo;
    }

    public function markPaymentCompleted(\DateTimeInterface $paidAt): void
    {
        $this->payment->complete($paidAt);
    }

    public function markPaymentCanceled(): void
    {
        $this->payment->cancel();
    }

    public function markPaymentPending(): void
    {
        $this->payment->pend();
    }

    public function markPaymentWaitingForConfirmation(): void
    {
        $this->payment->waitForConfirmation();
    }
}
