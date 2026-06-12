<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Exception;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\NotFoundException;

class ClientUserEmailNotFoundException extends NotFoundException
{
    public function __construct(array $details = [])
    {
        parent::__construct(ExceptionEnum::CLIENT_USER_EMAIL_NOT_FOUND, $details);
    }
}
