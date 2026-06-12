<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Exception;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\ValidationException;

class ClientUserCreationFailedException extends ValidationException
{
    public function __construct()
    {
        parent::__construct(ExceptionEnum::CLIENT_USER_CREATION_FAILED);
    }
}
