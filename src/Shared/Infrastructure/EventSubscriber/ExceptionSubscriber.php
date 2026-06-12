<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\EventSubscriber;

use App\Shared\Infrastructure\Http\Exception\Handler\CompositeExceptionHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(private CompositeExceptionHandler $exceptionHandler)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => [['onKernelException', -125]]];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception         = $event->getException();
        $exceptionResponse = $this->exceptionHandler->handle($exception);
        $event->setResponse(new JsonResponse($exceptionResponse->toArray(), $exceptionResponse->httpCode));
    }
}
