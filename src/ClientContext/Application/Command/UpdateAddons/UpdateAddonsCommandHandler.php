<?php declare(strict_types=1);

namespace App\ClientContext\Application\Command\UpdateAddons;

use App\ClientContext\Domain\Repository\ClientLicenseRepositoryInterface;
use App\ClientContext\Domain\Repository\ClientRepositoryInterface;
use App\ClientContext\Domain\Exception\ClientNotFoundException;
use App\ClientContext\Domain\Event\LicenseAddonUpdated;
use App\ClientContext\Application\Factory\ClientLicenseFactory;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
class UpdateAddonsCommandHandler
{
    public function __construct(
        private ClientLicenseFactory $clientLicenseFactory,
        private ClientRepositoryInterface $clientRepository,
        private ClientLicenseRepositoryInterface $clientLicenseRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(UpdateAddonsCommand $command): void
    {
        $client = $this->clientRepository->findOneBySubdomainForLicenseDetails($command->getSubdomain()->getValue());

        if (!$client) {
            throw new ClientNotFoundException();
        }

        foreach ($client->getActiveClientLicenses() as $activeClientLicense) {
            $activeClientLicense = $this->clientLicenseFactory->updateActiveAddons(
                $command->getAddonUpdates(),
                $command->getDeviceUpdates(),
                $activeClientLicense,
            );
            $this->clientLicenseRepository->update($activeClientLicense);

            $this->eventDispatcher->dispatch(new LicenseAddonUpdated(
                $command->getSubdomain()->getValue(),
                $activeClientLicense->getId(),
            ));
        }
    }
}
