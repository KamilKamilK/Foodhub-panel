<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\Exception\Handler;

use App\Shared\Application\DTO\ExceptionResponseDTO;
use App\Shared\Domain\Exception\AppException;
use Symfony\Contracts\Translation\TranslatorInterface;

final class AppExceptionHandler implements ExceptionHandlerInterface
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function supports(\Exception $exception): bool
    {
        return $exception instanceof AppException;
    }

    public function handle(\Exception $exception): ExceptionResponseDTO
    {
        /** @var AppException $exception */
        $message = $this->translator->trans(
            sprintf('exception.%s', $exception->getAppCode()), [], 'exception'
        );

        return new ExceptionResponseDTO(
            $exception->getAppCode(),
            $message,
            $exception->getHttpCode(),
            $exception->getDetails(),
        );
    }
}
