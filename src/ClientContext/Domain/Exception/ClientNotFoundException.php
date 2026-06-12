<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Exception;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\NotFoundException;

final class ClientNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct(ExceptionEnum::CLIENT_NOT_FOUND);
    }
}
