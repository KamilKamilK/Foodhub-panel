<?php declare(strict_types=1);

namespace App\ClientContext\DataFixtures;

use App\ClientContext\Domain\Entity\Client;
use App\ClientContext\Domain\Entity\ClientUser;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadClientUserData extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['dev'];
    }

    /**
     * Data for fixture
     *
     * @return array
     */
    private function getData()
    {
        return [
            [
                'referenceClient' => 'gastroonlinemaster',
                'email' => 'admin@lsisoftware.pl',
                'active' => true,
            ],
            [
                'referenceClient' => 'gastroonlinemaster',
                'email' => 'manager@lsisoftware.pl',
                'active' => true,
            ],
            [
                'referenceClient' => 'gastroonlinemaster',
                'email' => 'kasjer@lsisoftware.pl',
                'active' => true,
            ]
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $item) {
            /** @var Client $client */
            $client = $this->getReference($item['referenceClient'], Client::class);
            $clientUser = ClientUser::forAdminCreation(
                email:  $item['email'],
                client: $client,
                active: true,
            );

            $manager->persist($clientUser);
            $manager->flush();

            $nameToReference = strtolower(str_replace(' ', '-', $item['email']));
            $this->addReference("user-user-{$nameToReference}", $clientUser);
        }
    }

    public function getDependencies(): array
    {
        return [
            LoadClientData::class,
        ];
    }
}
