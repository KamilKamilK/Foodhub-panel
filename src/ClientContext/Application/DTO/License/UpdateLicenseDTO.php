<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO\License;

use App\Shared\Application\DTO\BaseDTO;

class UpdateLicenseDTO extends BaseDTO
{
    /**
     * @var ?string
     */
    public ?string $subdomain = null;

    /**
     * @var array<UpdateLicenseAddonsDTO>
     */
    public array $addons = [];

    /**
     * @var array<UpdateLicenseAdditionalDevicesDTO>
     */
    public array $additionalDevices = [];
}
