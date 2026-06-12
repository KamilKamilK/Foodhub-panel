<?php declare(strict_types=1);

namespace App\ClientContext\Domain\Repository;

use App\ClientContext\Domain\Entity\Agreement;

interface AgreementRepositoryInterface
{
    /** @return Agreement[] */
    public function findByLocale(string $locale): array;

    /** @return Agreement[] */
    public function findByIds(array $agreementsIds): array;
}
