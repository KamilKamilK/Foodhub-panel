<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Repository;

use App\Shared\Domain\Entity\PlaceType;
use App\Shared\Domain\Repository\PlaceTypeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PlaceTypeRepository extends ServiceEntityRepository implements PlaceTypeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlaceType::class);
    }

    public function findByLocale(string $locale): array
    {
        return $this->findBy(['locale' => strtolower($locale)], ['position' => 'ASC']);
    }
}
