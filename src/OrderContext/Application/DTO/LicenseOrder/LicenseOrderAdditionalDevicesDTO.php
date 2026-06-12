<?php declare(strict_types=1);

namespace App\OrderContext\Application\DTO\LicenseOrder;

use App\Shared\Application\DTO\BaseDTO;

class LicenseOrderAdditionalDevicesDTO extends BaseDTO
{
    /**
     * @var ?int
     */
    public ?int $additionalDeviceId;

    /**
     * @var ?int
     */
    public ?int $quantity;
}
