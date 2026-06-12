<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Exception;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\NotFoundException;

class ClientUserWithTokenNotFoundException extends NotFoundException
{
    public function __construct(array $details = [])
    {
        parent::__construct(ExceptionEnum::CLIENT_USER_WITH_TOKEN_NOT_FOUND, $details);
    }
}
