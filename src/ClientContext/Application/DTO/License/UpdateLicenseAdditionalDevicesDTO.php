<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO\License;

use App\Shared\Application\DTO\BaseDTO;

class UpdateLicenseAdditionalDevicesDTO extends BaseDTO
{
    public string $deviceType = '';
    public int $quantity      = 0;
}
