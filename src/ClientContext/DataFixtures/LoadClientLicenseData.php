<?php declare(strict_types=1);

namespace App\ClientContext\DataFixtures;

use App\ClientContext\Domain\Entity\Client;
use App\ClientContext\Domain\Entity\ClientLicense;
use App\LicenseContext\Domain\Entity\License;
use App\LicenseContext\Domain\Repository\LicenseRepositoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadClientLicenseData extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->assignTrialLicenseToExistedClients($manager);
    }

    private function assignTrialLicenseToExistedClients(ObjectManager $manager): void
    {
        $clients = $manager->getRepository(Client::class)->findAll();
        /** @var LicenseRepositoryInterface $licenseRepository */
        $licenseRepository = $manager->getRepository(License::class);
        $trialLicense = $licenseRepository->findTrialLicense();

        /** @var Client $client */
        foreach ($clients as $client) {
            if ($client->getClientLicenses()->count() === 0) {
                $clientLicense = ClientLicense::forTrial(
                    license:      $trialLicense,
                    client:       $client,
                    durationDays: (int) $_ENV['TRIAL_PERIOD_LENGTH'],
                    specialCode:  null,
                );

                $manager->persist($clientLicense);
                $manager->flush();
            }
        }
    }

    public function getDependencies(): array
    {
        return [
            \App\LicenseContext\DataFixtures\LoadLicenseData::class,
            LoadClientData::class,
        ];
    }
}
