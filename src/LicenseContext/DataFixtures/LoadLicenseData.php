<?php declare(strict_types=1);

namespace App\LicenseContext\DataFixtures;

use App\LicenseContext\Application\Service\LicenseSeedingService;
use App\LicenseContext\Application\Service\LicenseSetSeedingService;
use App\LicenseContext\Infrastructure\Seed\LicenseSeedData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadLicenseData extends Fixture
{
    public function __construct(
        private LicenseSeedingService $licenseSeedingService,
        private LicenseSetSeedingService $licenseSetSeedingService,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->licenseSeedingService->seedFromData(LicenseSeedData::getLicenseData());
        $this->licenseSetSeedingService->seedFromData(LicenseSeedData::getSetData());
    }
}
