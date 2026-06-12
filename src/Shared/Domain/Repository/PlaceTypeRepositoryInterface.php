<?php declare(strict_types=1);

namespace App\Shared\Domain\Repository;

use App\Shared\Domain\Entity\PlaceType;

interface PlaceTypeRepositoryInterface
{
    /** @return PlaceType[] */
    public function findByLocale(string $locale): array;
}
