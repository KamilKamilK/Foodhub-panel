<?php declare(strict_types=1);

namespace App\Shared\Application\Query\GetPlacesTypes;

use App\Shared\Domain\Repository\PlaceTypeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetPlacesTypesQueryHandler
{
    public function __construct(
        private PlaceTypeRepositoryInterface $placeTypeRepository,
    ) {
    }

    public function __invoke(GetPlacesTypesQuery $query): array
    {
        return $this->placeTypeRepository->findByLocale($query->getLocale());
    }
}
