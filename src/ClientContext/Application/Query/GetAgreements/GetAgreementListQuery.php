<?php declare(strict_types=1);

namespace App\ClientContext\Application\Query\GetAgreements;

class GetAgreementListQuery
{
    public function __construct(private readonly string $locale)
    {
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
