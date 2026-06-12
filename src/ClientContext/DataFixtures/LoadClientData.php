<?php declare(strict_types=1);

namespace App\ClientContext\DataFixtures;

use App\ClientContext\Domain\Entity\Address;
use App\ClientContext\Domain\Entity\Client;
use App\ClientContext\Domain\Entity\Company;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadClientData extends Fixture implements FixtureGroupInterface
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
                'company' => [
                    'name' => 'Firma 1 sp. z o.o',
                    'shortName' => 'Firma 1',
                    'taxIdNumber' => 'NIP11111111',
                    'registrationNumber' => '11111111111',
                ],
                'address' => [
                    'street' => 'Przybyszewskiego',
                    'buildingNo' => '145',
                    'localNo' => null,
                    'zipCode' => '60-600',
                    'country' => 'Polska',
                    'city' => 'Łódź'
                ],
                'subdomain' => 'localhost',
                'dbName' => 'gastroonlinemaster',
                'dbPassword' => 'gastroonlinemaster',
                'dbUser' => 'gastroonlinemaster'
            ],
            [
                'company' => [
                    'name' => 'Firma 2 sp. z o.o',
                    'shortName' => 'Firma 2',
                    'taxIdNumber' => 'NIP22222222',
                    'registrationNumber' => '22222222222',
                ],
                'address' => [
                    'street' => 'Przybyszewskiego',
                    'buildingNo' => '146',
                    'localNo' => null,
                    'zipCode' => '60-601',
                    'country' => 'Polska',
                    'city' => 'Łódź'
                ],
                'subdomain' => 'localhost2',
                'dbName' => 'gastroonlinemaster2',
                'dbPassword' => 'gastroonlinemaster2',
                'dbUser' => 'gastroonlinemaster2'
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $item) {
            $address = Address::create(
                street:     $item['address']['street'],
                buildingNo: $item['address']['buildingNo'],
                localNo:    $item['address']['localNo'],
                city:       $item['address']['city'],
                zipCode:    $item['address']['zipCode'],
                country:    $item['address']['country'],
            );

            $company = Company::create(
                name:               $item['company']['name'],
                shortName:          $item['company']['shortName'],
                taxIdNumber:        $item['company']['taxIdNumber'],
                registrationNumber: $item['company']['registrationNumber'],
            );

            $client = Client::fromRegistration(
                firstname:   '',
                lastname:    '',
                phone:       null,
                email:       null,
                specialCode: null,
                dbUser:      $item['dbUser'],
                dbName:      $item['dbName'],
                dbPassword:  $item['dbPassword'],
                subdomain:   $item['subdomain'],
            );
            $client->setAddress($address);
            $client->setCompany($company);

            $manager->persist($client);
            $manager->flush();
            $this->addReference($item['dbName'], $client);
        }
    }
}
