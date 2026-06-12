<?php declare(strict_types=1);

namespace App\OrderContext\Application\Command\PayUWebhook;

use App\ClientContext\Application\DTO\License\AddonProvision;
use App\ClientContext\Application\DTO\License\DeviceProvision;
use App\ClientContext\Application\DTO\License\LicenseProvisioningRequest;
use App\ClientContext\Application\Factory\ClientLicenseFactory;
use App\MerchantContext\Domain\Repository\MerchantRepositoryInterface;
use App\OrderContext\Domain\Entity\ClientLicenseOrder;
use App\OrderContext\Domain\Enum\LicenseOrderTypeEnum;
use App\OrderContext\Domain\Enum\PaymentStatusEnum;
use App\OrderContext\Domain\Event\PaymentCompleted;
use App\OrderContext\Domain\Repository\ClientLicenseOrderRepositoryInterface;
use App\OrderContext\Domain\Repository\ClientLicenseRepositoryInterface;
use App\OrderContext\Application\Service\PayUGatewayInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
class PayUWebhookCommandHandler
{
    public function __construct(
        private PayUGatewayInterface $payUGateway,
        private ClientLicenseOrderRepositoryInterface $clientLicenseOrderRepository,
        private ClientLicenseRepositoryInterface $clientLicenseRepository,
        private ClientLicenseFactory $clientLicenseFactory,
        private MerchantRepositoryInterface $merchantRepository,
        private EventDispatcherInterface $eventDispatcher,
        private HttpClientInterface $httpClient,
        private string $mailerSendUrl,
    ) {
    }

    public function __invoke(PayUWebhookCommand $command): void
    {
        $order = $this->payUGateway->handleWebhook($command->getBody());

        if (
            $order->getPayment()->getStatus() === PaymentStatusEnum::COMPLETED->value &&
            !$order->getClientLicense()
        ) {
            $provisioning = $this->buildProvisioningRequest($order);

            if (
                $order->getOrderType() === LicenseOrderTypeEnum::NEW_LICENSE->value ||
                $order->getOrderType() === LicenseOrderTypeEnum::LICENSE_AUTO_RENEWAL->value
            ) {
                $license = $this->clientLicenseFactory->createLicense($provisioning);
                $order->assignLicense($license);
                $this->clientLicenseRepository->create($license);

                if ($order->getSelectedSet()) {
                    try {
                        $this->sendMailWithSelectedSet($order);
                    } catch (\Exception) {
                    }
                }
            }

            $this->clientLicenseFactory->updateActiveLicenses($provisioning);

            $this->eventDispatcher->dispatch(new PaymentCompleted(
                $order->getOrderId(),
                $order->getSubdomain(),
                $order->getOrderType(),
                $order->getPayment()->getTotalPriceGross()->getValueAsFloat(),
            ));
        }

        $this->clientLicenseOrderRepository->update($order);
    }

    private function buildProvisioningRequest(ClientLicenseOrder $order): LicenseProvisioningRequest
    {
        $addons = array_map(
            fn($orderAddon) => new AddonProvision($orderAddon->getAddon()),
            $order->getSelectedAddons()->toArray(),
        );

        $devices = array_map(
            fn($orderDevice) => new DeviceProvision($orderDevice->getDevice(), $orderDevice->getQuantity()),
            $order->getAdditionalDevices()->toArray(),
        );

        return new LicenseProvisioningRequest(
            client:      $order->getClient(),
            license:     $order->getLicense(),
            period:      $order->getPeriod(),
            specialCode: $order->getSpecialCode(),
            orderType:   $order->getOrderType(),
            addons:      $addons,
            devices:     $devices,
        );
    }

    private function sendMailWithSelectedSet(ClientLicenseOrder $order): void
    {
        foreach ($this->merchantRepository->findDefault() as $merchant) {
            $this->httpClient->request('POST', $this->mailerSendUrl, [
                'http_version' => '1.0',
                'headers'      => ['locale' => 'pl'],
                'json'         => [
                    'project' => 'gastroonline',
                    'to'      => ['name' => $merchant->getFirstName() . ' ' . $merchant->getLastName(), 'email' => $merchant->getEmail()],
                    'message' => [
                        'identity' => 'license:order_set',
                        'params'   => [
                            'selectedSet'       => $order->getSelectedSet()->getTranslation('pl')->getName(),
                            'clientName'        => $order->getClient()->getFullName(),
                            'clientEmail'       => $order->getClient()->getEmail(),
                            'clientPhone'       => $order->getClient()->getPhone(),
                            'clientSpecialCode' => $order->getSpecialCode() ?? $order->getClient()->getRegSpecialCode(),
                            'clientIdentifier'  => $order->getClient()->getSubdomain(),
                        ],
                    ],
                ],
            ]);
        }
    }
}
