<?php declare(strict_types=1);

namespace App\OrderContext\Infrastructure\Gateway;

use App\OrderContext\Domain\Repository\ClientLicenseOrderRepositoryInterface;
use App\OrderContext\Domain\Entity\ClientLicenseOrder;
use App\OrderContext\Domain\Enum\LicenseOrderTypeEnum;
use App\OrderContext\Domain\Enum\PaymentMethodTypeEnum;
use App\OrderContext\Domain\Enum\PaymentStatusEnum;
use App\OrderContext\Domain\Exception\OrderNotFoundException;
use App\OrderContext\Domain\Exception\PaymentException;
use App\OrderContext\Domain\Exception\UnhandledPaymentMethodException;
use OauthCacheFile;
use OpenPayU_Configuration;
use OpenPayU_Order;
use OpenPayU;
use OpenPayU_Retrieve;
use App\OrderContext\Application\Service\InvoiceNumbersService;
use App\OrderContext\Application\Service\PayUGatewayInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PayUGateway extends OpenPayU implements PayUGatewayInterface
{
    public function __construct(
        private string $cacheDir,
        private string $defaultLocale,
        private string $payuEnv,
        private string $payuPosId,
        private string $payuMd5,
        private string $payuClientId,
        private string $payuClientSecret,
        private ClientLicenseOrderRepositoryInterface $clientLicenseOrderRepository,
        private RouterInterface $router,
        private TranslatorInterface $translator,
        private InvoiceNumbersService $invoiceNumbersService,
    ) {
    }

    public function makePayment(ClientLicenseOrder $orderEntity): ClientLicenseOrder
    {
        if ($orderEntity->getOrderType() === LicenseOrderTypeEnum::NEW_ADDONS->value) {
            $orderDescription = $this->translator->trans('license.order.addons');
        } else {
            $orderDescription = $this->translator->trans('license.order.license');
        }

        $this->configurePayU();

        $order = [];
        $order['continueUrl'] = $orderEntity->getPayment()->getContinueUrl();
        $order['notifyUrl'] = $this->router->generate(
            'payments_webhook_payu', [], UrlGenerator::ABSOLUTE_URL
        );

        $order['customerIp'] = $_SERVER['REMOTE_ADDR'];
        $order['merchantPosId'] = OpenPayU_Configuration::getMerchantPosId();
        $order['description'] = $orderDescription;
        $order['currencyCode'] = $orderEntity->getPayment()->getCurrency();
        $order['totalAmount'] = (int)ceil($orderEntity->getPayment()->getTotalPriceGross()->getValueAsFloat() * 100);
        $order['extOrderId'] = $orderEntity->getOrderId();

        $order['products'][0]['name'] = $orderDescription;
        $order['products'][0]['unitPrice'] = (int)ceil($orderEntity->getPayment()->getTotalPriceGross()->getValueAsFloat() * 100);
        $order['products'][0]['quantity'] = 1;

        $order['buyer']['language'] = $this->defaultLocale;

        if ($orderEntity->getPayment()->getPaymentType() === PaymentMethodTypeEnum::CARD_TOKEN->value) {
            $order['payMethods']['payMethod']['type'] = 'CARD_TOKEN';
            $order['payMethods']['payMethod']['value'] = $orderEntity->getPayment()->getPaymentTypeValue();
        } elseif ($orderEntity->getPayment()->getPaymentType() === PaymentMethodTypeEnum::BLIK->value) {
            $order['payMethods']['payMethod']['type'] = 'PBL';
            $order['payMethods']['payMethod']['value'] = 'blik';
            $order['payMethods']['payMethod']['authorizationCode'] = $orderEntity->getPayment()->getPaymentTypeValue();
        } elseif ($orderEntity->getPayment()->getPaymentType() === PaymentMethodTypeEnum::OTHER->value) {
            $order['payMethods']['payMethod']['type'] = 'PBL';
            $order['payMethods']['payMethod']['value'] = $orderEntity->getPayment()->getPaymentTypeValue();
        } else {
            $orderEntity->markPaymentCanceled();
            $this->clientLicenseOrderRepository->update($orderEntity);

            throw new UnhandledPaymentMethodException();
        }

        try {
            $providerResponse = OpenPayU_Order::create($order);
            $response = $providerResponse->getResponse();
            $orderEntity->markPaymentPending();
            $orderEntity->getPayment()->recordPaymentAttempt(
                $response->orderId,
                $response->redirectUri ?? null,
            );

            return $orderEntity;
        } catch (\Exception $e) {
            $orderEntity->markPaymentCanceled();
            $this->clientLicenseOrderRepository->update($orderEntity);

            throw new PaymentException(['message' => $e->getMessage()]);
        }
    }

    public function getPaymentMethods(): array
    {
        $this->configurePayU();

        $response = OpenPayU_Retrieve::payMethods($this->defaultLocale);
        $excludedPm = ['ap', 'jp', 'c', 'blik']; //Google Pay, Apple Pay, Credit Card, BLIK - będą jako osobne opcje

        $paymentMethods = array_filter(
            json_decode(json_encode($response->getResponse()->payByLinks), true),
            fn($item) =>
                $item['status'] !== 'DISABLED' &&
                !in_array($item['value'], $excludedPm)
        );

        return array_values($paymentMethods);
    }

    private function configurePayU(): void
    {
        @mkdir($this->cacheDir);
        OpenPayU_Configuration::setOauthTokenCache(new OauthCacheFile($this->cacheDir));
        OpenPayU_Configuration::setEnvironment($this->payuEnv);
        OpenPayU_Configuration::setMerchantPosId($this->payuPosId);
        OpenPayU_Configuration::setSignatureKey($this->payuMd5);
        OpenPayU_Configuration::setOauthClientId($this->payuClientId);
        OpenPayU_Configuration::setOauthClientSecret($this->payuClientSecret);
    }

    public function handleWebhook(string $body): ClientLicenseOrder
    {
        $providerRequest = json_decode($body, true);
        $order = $this->clientLicenseOrderRepository->findOneByOrderId($providerRequest['order']['extOrderId']);
        if (!$order) {
            throw new OrderNotFoundException();
        }

        if (
            $providerRequest['order']['status'] === PaymentStatusEnum::COMPLETED->value &&
            $order->getPayment()->getStatus() !== PaymentStatusEnum::COMPLETED->value
        ) {
            $order->markPaymentCompleted(new \DateTime());

            if (!$order->getInvoiceNo()) {
                $order->markInvoiced($this->invoiceNumbersService->getInvoiceNumber());
            }
        } elseif ($providerRequest['order']['status'] === PaymentStatusEnum::CANCELED->value) {
            $order->markPaymentCanceled();
        } elseif ($providerRequest['order']['status'] === PaymentStatusEnum::PENDING->value) {
            $order->markPaymentPending();
        } elseif ($providerRequest['order']['status'] === PaymentStatusEnum::WAITING_FOR_CONFIRMATION->value) {
            $order->markPaymentWaitingForConfirmation();
        }

        return $order;
    }
}
