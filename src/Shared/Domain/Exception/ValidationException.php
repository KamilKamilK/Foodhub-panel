<?php declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;

class ValidationException extends AppException
{
    const HTTP_CODE = Response::HTTP_BAD_REQUEST;

    public function __construct(string $appCode, array $details = [], array $props = [])
    {
        parent::__construct($appCode, self::HTTP_CODE, $details);
    }
}
