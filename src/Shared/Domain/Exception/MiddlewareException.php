<?php declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;

class MiddlewareException extends AppException
{
    const HTTP_CODE = Response::HTTP_BAD_REQUEST;

    public function __construct(array $details = [])
    {
        parent::__construct($details['code'], self::HTTP_CODE, $details);
    }
}
