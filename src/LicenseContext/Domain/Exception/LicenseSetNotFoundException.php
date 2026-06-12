<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Exception;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\NotFoundException;

final class LicenseSetNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct(ExceptionEnum::LICENSE_SET_NOT_FOUND);
    }
}
