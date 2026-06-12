<?php declare (strict_types = 1);

namespace App\Shared\Domain\Exception\Update;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\ValidationException;

class ApkVersionIsDelightedException extends ValidationException
{
    public function __construct()
    {
        parent::__construct(ExceptionEnum::UPDATE_APK_VERSION_IS_DELIGHTED);
    }
}
