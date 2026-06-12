<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Exception;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\ValidationException;

class DatabaseCreationFailedException extends ValidationException
{
    public function __construct()
    {
        parent::__construct(ExceptionEnum::DATABASE_CREATION_FAILED);
    }
}
