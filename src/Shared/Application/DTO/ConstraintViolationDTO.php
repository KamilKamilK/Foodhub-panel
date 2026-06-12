<?php declare(strict_types=1);

namespace App\Shared\Application\DTO;

final class ConstraintViolationDTO extends BaseDTO
{
    public function __construct(
        public readonly string $code,
        public readonly string $message,
    ) {
    }
}
