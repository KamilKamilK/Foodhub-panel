<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Exception;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\ValidationException;

final class TotalPriceMismatchException extends ValidationException
{
    public function __construct(array $details = [])
    {
        parent::__construct(ExceptionEnum::LICENSE_TOTAL_PRICE_MISMATCH, $details);
    }
}
