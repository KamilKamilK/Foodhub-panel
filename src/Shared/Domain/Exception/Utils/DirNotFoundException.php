<?php declare(strict_types=1);

namespace App\Shared\Domain\Exception\Utils;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\NotFoundException;

class DirNotFoundException extends NotFoundException
{
    public function __construct()
    {
        parent::__construct(ExceptionEnum::DIR_NOT_FOUND);
    }
}
