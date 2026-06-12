<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Exception\Handler;

use App\Shared\Application\DTO\ExceptionResponseDTO;

final class CompositeExceptionHandler implements ExceptionHandlerInterface
{
    /** @param ExceptionHandlerInterface[] $handlers */
    public function __construct(
        private array $handlers,
        private ExceptionHandlerInterface $defaultHandler,
    ) {
    }

    public function supports(\Exception $exception): bool
    {
        return true;
    }

    public function handle(\Exception $exception): ExceptionResponseDTO
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($exception)) {
                return $handler->handle($exception);
            }
        }

        return $this->defaultHandler->handle($exception);
    }
}
