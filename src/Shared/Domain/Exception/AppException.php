<?php declare(strict_types=1);

namespace App\Shared\Domain\Exception;

abstract class AppException extends \Exception
{
    public function __construct(
        private readonly string $appCode,
        private readonly int $httpCode,
        private readonly array $details = [],
    ) {
        parent::__construct();
    }

    public function getAppCode(): string
    {
        return $this->appCode;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getDetails(): array
    {
        return $this->details;
    }
}
