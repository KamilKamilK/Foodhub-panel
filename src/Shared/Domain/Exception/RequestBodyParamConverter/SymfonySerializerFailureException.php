<?php declare(strict_types=1);

namespace App\Shared\Domain\Exception\RequestBodyParamConverter;

use App\Shared\Domain\Enum\Exception\ExceptionEnum;
use App\Shared\Domain\Exception\ValidationException;

class SymfonySerializerFailureException extends ValidationException
{
    public function __construct()
    {
        parent::__construct(ExceptionEnum::REQUEST_BODY_PARAM_CONVERTER_SYMFONY_SERIALIZER_FAILURE);
    }
}
