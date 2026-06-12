<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Exception;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\NotFoundException;

final class ClientUserNotFoundException extends NotFoundException
{
    public function __construct(array $details = [])
    {
        parent::__construct(ExceptionEnum::CLIENT_USER_NOT_FOUND, $details);
    }
}
