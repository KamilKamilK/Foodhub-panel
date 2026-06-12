<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Exception\Handler;

use App\Shared\Application\DTO\ExceptionResponseDTO;

interface ExceptionHandlerInterface
{
    public function supports(\Exception $exception): bool;
    public function handle(\Exception $exception): ExceptionResponseDTO;
}
