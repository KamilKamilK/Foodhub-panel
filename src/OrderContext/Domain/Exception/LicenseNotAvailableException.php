<?php declare(strict_types=1);

namespace App\OrderContext\Domain\Exception;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\NotFoundException;

final class LicenseNotAvailableException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct(ExceptionEnum::LICENSE_NOT_FOUND);
    }
}
