<?php declare(strict_types=1);

namespace App\ClientContext\Application\DTO\License;

use App\Shared\Application\DTO\BaseDTO;

class UpdateLicenseAddonsDTO extends BaseDTO
{
    public string $addonType           = '';
    public string $addonCategory       = '';
    public bool $isActiveOnNextPeriod  = false;
}
