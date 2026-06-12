<?php declare(strict_types=1);

namespace App\LicenseContext\Application\ConsoleCommand;

use App\LicenseContext\Infrastructure\Seed\LicenseSeedData;
use App\LicenseContext\Application\Service\LicenseSeedingService;
use App\LicenseContext\Application\Service\LicenseSetSeedingService;
use App\LicenseContext\Application\Service\TrialLicenseAssignmentService;
use App\LicenseContext\Domain\Repository\LicenseRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:license:migration', description: 'Command for licenses migration')]
class LicenseMigrationCommand extends Command
{
    public function __construct(
        private readonly LicenseRepositoryInterface $licenseRepository,
        private readonly LicenseSeedingService $licenseSeedingService,
        private readonly LicenseSetSeedingService $licenseSetSeedingService,
        private readonly TrialLicenseAssignmentService $trialLicenseAssignmentService,
        ?string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription('Command for licenses migration');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (count($this->licenseRepository->findAllLicenses()) > 0) {
            $output->writeln('<error>Migration already executed — licenses already exist.</error>');
            return Command::FAILURE;
        }

        $this->licenseSeedingService->seedFromData(LicenseSeedData::getLicenseData());
        $this->licenseSetSeedingService->seedFromData(LicenseSeedData::getSetData());

        $this->trialLicenseAssignmentService->assignToClientsWithoutLicense();

        return Command::SUCCESS;
    }
}
