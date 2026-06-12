<?php declare(strict_types=1);

namespace App\Shared\Application\DTO;

final class ExceptionResponseDTO extends BaseDTO
{
    public function __construct(
        public readonly string $code,
        public readonly string $message,
        public readonly int $httpCode,
        public readonly array $details = [],
        public readonly array $props = [],
    ) {
    }

    public function toArray(): array
    {
        $arr = parent::toArray();
        unset($arr['httpCode']);

        if (empty($arr['details'])) {
            unset($arr['details']);
        }

        if (empty($arr['props'])) {
            unset($arr['props']);
        }

        return $arr;
    }
}
