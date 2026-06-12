<?php declare(strict_types=1);

namespace App\Shared\Application\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class LocaleService
{
    public string $locale;

    public function __construct(RequestStack $requestStack)
    {
        $request = $requestStack->getCurrentRequest();
        $this->locale = $request->getLocale();
    }

    public function __toString()
    {
        return $this->locale;
    }
}
