<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Exception;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\NotFoundException;

class AgreementNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct(ExceptionEnum::AGREEMENTS_NOT_FOUND);
    }
}