<?php declare(strict_types=1);

namespace App\LicenseContext\Domain\Exception;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\ValidationException;

final class LicenseAssignedException extends ValidationException
{
    public function __construct()
    {
        parent::__construct(ExceptionEnum::LICENSE_ASSIGNED_ERROR);
    }
}
