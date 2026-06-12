<?php declare(strict_types=1);

namespace App\Shared\DataFixtures;

use App\Shared\Domain\Entity\PlaceType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlacesTypesFixtures extends Fixture
{
    public static function getData(): array
    {
        return [
            [
                'label' => 'restaurant',
                'name' => 'Restaurant',
                'icon' => 'flaticon-restaurant-business',
                'locale' => 'en',
                'position' => 1
            ],
            [
                'label' => 'food-truck',
                'name' => 'Food Truck',
                'icon' => 'flaticon-food-track-business',
                'locale' => 'en',
                'position' => 2
            ],
            [
                'label' => 'cafe',
                'name' => 'Cafehouse',
                'icon' => 'flaticon-cafe-business',
                'locale' => 'en',
                'position' => 3
            ],
            [
                'label' => 'pizzeria',
                'name' => 'Pizzeria',
                'icon' => 'flaticon-pizzeria-business',
                'locale' => 'en',
                'position' => 4
            ],
            [
                'label' => 'other',
                'name' => 'Other',
                'icon' => 'flaticon-other-business',
                'locale' => 'en',
                'position' => 5
            ],
            [
                'label' => 'restaurant',
                'name' => 'Restauracja',
                'icon' => 'flaticon-restaurant-business',
                'locale' => 'pl',
                'position' => 1
            ],
            [
                'label' => 'food-truck',
                'name' => 'Food Truck',
                'icon' => 'flaticon-food-track-business',
                'locale' => 'pl',
                'position' => 2
            ],
            [
                'label' => 'cafe',
                'name' => 'Kawiarnia',
                'icon' => 'flaticon-cafe-business',
                'locale' => 'pl',
                'position' => 3
            ],
            [
                'label' => 'pizzeria',
                'name' => 'Pizzeria',
                'icon' => 'flaticon-pizzeria-business',
                'locale' => 'pl',
                'position' => 4
            ],
            [
                'label' => 'other',
                'name' => 'Inne',
                'icon' => 'flaticon-other-business',
                'locale' => 'pl',
                'position' => 5
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::getData() as $item) {
            $entity = PlaceType::create(
                label:    $item['label'],
                name:     $item['name'],
                icon:     $item['icon'],
                locale:   $item['locale'],
                position: $item['position'],
            );

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
