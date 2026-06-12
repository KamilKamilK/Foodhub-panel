<?php declare(strict_types=1);

namespace App\Shared\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;

abstract class NotFoundException extends AppException
{
    const HTTP_CODE = Response::HTTP_NOT_FOUND;

    public function __construct(string $appCode, array $details = [])
    {
        parent::__construct($appCode, self::HTTP_CODE, $details);
    }
}
