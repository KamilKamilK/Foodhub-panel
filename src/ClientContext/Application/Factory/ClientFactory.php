<?php declare(strict_types=1);

namespace App\ClientContext\Application\Factory;

use App\ClientContext\Application\Command\Setup\DTO\Db;
use App\ClientContext\Application\Command\Setup\SetupCommand;
use App\ClientContext\Domain\Repository\AgreementRepositoryInterface;
use App\ClientContext\Domain\Entity\Client;
use App\ClientContext\Domain\Entity\ClientAddon;
use App\ClientContext\Domain\Entity\ClientLicense;
use App\ClientContext\Application\Service\ClientTrialLicenseLookupInterface;
use App\LicenseContext\Domain\Enum\PeriodEnum;

class ClientFactory
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
        private AgreementRepositoryInterface $agreementRepository,
        private ClientTrialLicenseLookupInterface $trialLicenseLookup,
        private int $trialPeriodLength,
        private int $trialPeriodLengthWithCode,
    ) {
    }

    public function createFromSetupRequest(SetupCommand $command, Db $db): Client
    {
        $client = Client::fromRegistration(
            firstname:   $command->getUser()->getName(),
            lastname:    $command->getUser()->getSurname(),
            phone:       $command->getUser()->getPhone(),
            email:       $command->getUser()->getEmail(),
            specialCode: $command->getUser()->getSpecialCode(),
            dbUser:      $db->getUser(),
            dbName:      $db->getName(),
            dbPassword:  $db->getPassword(),
            subdomain:   $db->getName(),
        );

        $this->addAgreements($client, $command->getAgreementIds());
        $this->assignTrialLicense($client, $command);

        return $client;
    }

    private function addAgreements(Client $client, array $agreementIds): void
    {
        foreach ($this->agreementRepository->findByIds($agreementIds) as $agreement) {
            $client->addAgreement($agreement);
        }
    }

    private function assignTrialLicense(Client $client, SetupCommand $command): void
    {
        if ($client->getClientLicenses()->count() > 0) {
            return;
        }

        $trialLicense = $this->trialLicenseLookup->findTrialLicense();
        $days         = $command->getUser()->getSpecialCode()
            ? $this->trialPeriodLengthWithCode
            : $this->trialPeriodLength;

        ClientLicense::forTrial(
            license:      $trialLicense,
            client:       $client,
            durationDays: $days,
            specialCode:  $command->getUser()->getSpecialCode(),
        );
    }
}
