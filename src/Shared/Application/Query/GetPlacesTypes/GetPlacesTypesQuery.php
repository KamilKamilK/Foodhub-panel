<?php declare(strict_types=1);

namespace App\Shared\Application\Query\GetPlacesTypes;

class GetPlacesTypesQuery
{
    public function __construct(private readonly string $locale)
    {
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
