<?php declare(strict_types=1);

namespace App\OrderContext\Application\DTO\LicenseOrder;

use App\OrderContext\Domain\ValueObject\BuyerData;
use App\Shared\Application\DTO\BaseDTO;

abstract class AbstractLicenseOrderDTO extends BaseDTO
{
    /**
     * @var ?string
     */
    public ?string $subdomain = null;

    /**
     * @var ?string
     */
    public ?string $specialCode = null;

    /**
     * @var array<int>
     */
    public array $addons = [];

    /**
     * @var array<LicenseOrderAdditionalDevicesDTO>
     */
    public array $additionalDevices = [];

    /**
     * @var LicenseOrderPaymentDTO
     */
    public LicenseOrderPaymentDTO $payment;

    /**
     * @var ?string
     */
    public ?string $buyerName = null;

    /**
     * @var ?string
     */
    public ?string $buyerVatNumber = null;

    /**
     * @var ?string
     */
    public ?string $buyerStreet = null;

    /**
     * @var ?string
     */
    public ?string $buyerHouse = null;

    /**
     * @var ?string
     */
    public ?string $buyerFlat = null;

    /**
     * @var ?string
     */
    public ?string $buyerCity = null;

    /**
     * @var ?string
     */
    public ?string $buyerZip = null;

    public function toBuyerData(): BuyerData
    {
        return new BuyerData(
            name:       $this->buyerName ?? '',
            vatNumber:  $this->buyerVatNumber ?? '',
            street:     $this->buyerStreet ?? '',
            house:      $this->buyerHouse ?? '',
            flat:       $this->buyerFlat,
            city:       $this->buyerCity ?? '',
            zip:        $this->buyerZip ?? '',
        );
    }
}
