<?php declare(strict_types=1);

namespace App\OrderContext\Application\Command\OrderAddonsCommand;

use App\OrderContext\Application\Service\LicenseOrderFactoryInterface;
use App\OrderContext\Domain\Event\LicenseOrdered;
use App\OrderContext\Domain\Repository\ClientLicenseOrderRepositoryInterface;
use App\OrderContext\Application\Service\PayUGatewayInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsMessageHandler]
class OrderAddonsCommandHandler
{
    public function __construct(
        private PayUGatewayInterface $payUGateway,
        private LicenseOrderFactoryInterface $licenseOrderFactory,
        private ClientLicenseOrderRepositoryInterface $clientLicenseOrderRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(OrderAddonsCommand $command): string
    {
        $order = $this->licenseOrderFactory->createFromOrderAddonsRequest($command->getRequest());
        $this->clientLicenseOrderRepository->create($order);

        $order = $this->payUGateway->makePayment($order);
        $this->clientLicenseOrderRepository->update($order);

        $this->eventDispatcher->dispatch(new LicenseOrdered(
            $order->getOrderId(),
            $order->getSubdomain(),
            $order->getLicense()->getId(),
            $order->getPeriod(),
        ));

        return $order->getOrderId();
    }
}
