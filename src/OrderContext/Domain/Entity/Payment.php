<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Entity;

use App\OrderContext\Domain\Enum\PaymentStatusEnum;
use App\Shared\Domain\ValueObject\Decimal;

class Payment
{
    private ?int $id = null;
    private \DateTime $createdAt;
    private ?\DateTimeInterface $paidAt = null;
    private string $paymentType;
    private ?string $paymentTypeValue = null;
    private string $status;
    private ?string $operationNumber = null;
    private string $currency;
    private Decimal $totalPriceNet;
    private Decimal $totalPriceGross;
    private string $continueUrl;
    private ?string $paymentUrl = null;
    private ClientLicenseOrder $clientLicenseOrder;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public static function initialize(
        string $paymentType,
        ?string $paymentTypeValue,
        string $currency,
        Decimal $totalPriceNet,
        Decimal $totalPriceGross,
        string $continueUrl,
        ClientLicenseOrder $order,
    ): self {
        $payment = new self();
        $payment->paymentType        = $paymentType;
        $payment->paymentTypeValue   = $paymentTypeValue;
        $payment->status             = PaymentStatusEnum::INITIALIZED->value;
        $payment->currency           = $currency;
        $payment->totalPriceNet      = $totalPriceNet;
        $payment->totalPriceGross    = $totalPriceGross;
        $payment->continueUrl        = $continueUrl;
        $payment->clientLicenseOrder = $order;
        return $payment;
    }

    public function getId(): ?int                      { return $this->id; }
    public function getCreatedAt(): \DateTime          { return $this->createdAt; }
    public function getPaidAt(): ?\DateTimeInterface   { return $this->paidAt; }
    public function getPaymentType(): ?string          { return $this->paymentType; }
    public function getPaymentTypeValue(): ?string     { return $this->paymentTypeValue; }
    public function getStatus(): ?string               { return $this->status; }
    public function getOperationNumber(): ?string      { return $this->operationNumber; }
    public function getCurrency(): ?string             { return $this->currency; }
    public function getTotalPriceNet(): Decimal        { return $this->totalPriceNet; }
    public function getTotalPriceGross(): Decimal      { return $this->totalPriceGross; }
    public function getContinueUrl(): ?string          { return $this->continueUrl; }
    public function getPaymentUrl(): ?string           { return $this->paymentUrl; }
    public function getClientLicenseOrder(): ?ClientLicenseOrder { return $this->clientLicenseOrder; }

    public function complete(\DateTimeInterface $paidAt): void
    {
        $this->status  = PaymentStatusEnum::COMPLETED->value;
        $this->paidAt  = $paidAt;
    }

    public function cancel(): void
    {
        $this->status = PaymentStatusEnum::CANCELED->value;
    }

    public function pend(): void
    {
        $this->status = PaymentStatusEnum::PENDING->value;
    }

    public function waitForConfirmation(): void
    {
        $this->status = PaymentStatusEnum::WAITING_FOR_CONFIRMATION->value;
    }

    public function recordPaymentAttempt(string $operationNumber, ?string $paymentUrl): void
    {
        $this->operationNumber = $operationNumber;
        $this->paymentUrl      = $paymentUrl;
    }
}
