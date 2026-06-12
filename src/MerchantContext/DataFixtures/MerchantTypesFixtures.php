<?php declare(strict_types=1);

namespace App\MerchantContext\DataFixtures;

use App\MerchantContext\Domain\Entity\Merchant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class MerchantTypesFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['dev'];
    }

    public static function getData(): array
    {
        return [
            [
                'email' => 'test-abc123@lsisoftware.pl',
                'phone' => '+48 666 777 888',
                'firstName' => 'Jaś',
                'lastName' => 'Fasola',
                'specialCode' => 'ABC123',
                'isDefault' => true
            ],
            [
                'email' => 'test-abc456@lsisoftware.pl',
                'phone' => '+48 666 777 889',
                'firstName' => 'Adam',
                'lastName' => 'Mickiewicz',
                'specialCode' => 'ABC456',
                'isDefault' => false
            ],
            [
                'email' => 'test-def123@lsisoftware.pl',
                'phone' => '+48 666 777 890',
                'firstName' => 'Geralt',
                'lastName' => 'ZRivii',
                'specialCode' => 'DEF123',
                'isDefault' => false
            ],
            [
                'email' => 'test-def456@lsisoftware.pl',
                'phone' => '+48 666 777 891',
                'firstName' => 'Kuba',
                'lastName' => 'Rozpruwacz',
                'specialCode' => 'DEF456',
                'isDefault' => false
            ],
            [
                'email' => 'test-ghi123@lsisoftware.pl',
                'phone' => '+48 666 777 892',
                'firstName' => 'Joanna',
                'lastName' => 'Wilk',
                'specialCode' => 'DEF789',
                'isDefault' => false
            ],
            [
                'email' => 'test-ghi456@lsisoftware.pl',
                'phone' => '+48 666 777 893',
                'firstName' => 'Mikołaj',
                'lastName' => 'Kopernik',
                'specialCode' => 'DEF135',
                'isDefault' => false
            ],
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::getData() as $item) {
            $entity = Merchant::create(
                email:       $item['email'],
                phone:       $item['phone'],
                firstName:   $item['firstName'],
                lastName:    $item['lastName'],
                specialCode: $item['specialCode'],
                isDefault:   $item['isDefault'],
            );

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
