<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Exception;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\NotFoundException;

final class UpdateLicenseAdditionalDeviceException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct(ExceptionEnum::LICENSE_ADDITIONAL_DEVICE_UPDATE_ERROR);
    }
}
