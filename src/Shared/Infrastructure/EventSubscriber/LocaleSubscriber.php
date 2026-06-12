<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\EventSubscriber;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private string $defaultLocale = 'en',
    ) {
    }

    public function onKernelRequest(KernelEvent $event): void
    {
        $request = $event->getRequest();
        $headers = $request->headers;

        if ($headers->get('accept-language')) {
            $locale = str_split($headers->get('accept-language'), 2)[0];
        } elseif ($headers->get('locale')) {
            $locale = $headers->get('locale');
        } else {
            $locale = $this->defaultLocale;
        }

        $request->setLocale(
            in_array($locale, $this->parameterBag->get('supported.languages'))
                ? $locale
                : $this->defaultLocale
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => [['onKernelRequest', 17]]];
    }
}
