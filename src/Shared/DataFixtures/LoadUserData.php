<?php declare(strict_types=1);

namespace App\Shared\DataFixtures;

use App\Shared\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoadUserData extends Fixture implements FixtureGroupInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }

    public function getData()
    {
        return [
            [
                'email' => 'admin@lsisoftware.pl',
                'plainPassword' => 'admin'
            ]
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $item) {
            $user = User::create($item['email'], $item['plainPassword']);
            $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getPlainPassword()));

            $manager->persist($user);
            $manager->flush();
        }
    }
}
