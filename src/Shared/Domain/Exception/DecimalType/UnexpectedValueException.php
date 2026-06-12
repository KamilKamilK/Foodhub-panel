<?php declare(strict_types=1);

namespace App\Shared\Domain\Exception\DecimalType;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\ValidationException;

class UnexpectedValueException extends ValidationException
{
    public function __construct()
    {
        parent::__construct(ExceptionEnum::DECIMAL_INVALID_VALUE);
    }
}
