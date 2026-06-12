<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Exception;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\ValidationException;

final class UnhandledPaymentMethodException extends ValidationException
{
    public function __construct()
    {
        parent::__construct(ExceptionEnum::LICENSE_UNHANDLED_PAYMENT_METHOD);
    }
}
