<?php declare(strict_types=1);

namespace App\OrderContext\Application\Factory;

use App\OrderContext\Application\DTO\LicenseOrder\LicenseOrderDTO;
use App\OrderContext\Application\DTO\LicenseOrder\OrderAddonsDTO;
use App\OrderContext\Domain\Entity\ClientLicenseOrder;
use App\OrderContext\Domain\Entity\ClientLicenseOrderAddon;
use App\OrderContext\Domain\Entity\ClientLicenseOrderDevice;
use App\OrderContext\Domain\Entity\Payment;
use App\OrderContext\Domain\Enum\LicenseOrderTypeEnum;
use App\OrderContext\Domain\Exception\OrderClientNotFoundException;
use App\OrderContext\Domain\Exception\LicenseAddonNotAvailableException;
use App\OrderContext\Domain\Exception\LicenseDeviceNotAvailableException;
use App\OrderContext\Domain\Exception\LicenseNotAvailableException;
use App\OrderContext\Domain\Exception\LicenseSetNotAvailableException;
use App\OrderContext\Domain\Exception\OrderNoFullPeriodsException;
use App\OrderContext\Domain\Exception\OrderUpgradeException;
use App\OrderContext\Domain\Exception\TotalPriceMismatchException;
use App\OrderContext\Application\Service\LicenseOrderFactoryInterface;
use App\OrderContext\Application\Service\OrderClientLookupInterface;
use App\OrderContext\Application\Service\OrderLicenseLookupInterface;
use App\OrderContext\Domain\Service\LicensePriceService;

class LicenseOrderFactory implements LicenseOrderFactoryInterface
{
    public function __construct(
        private readonly OrderClientLookupInterface $clientLookup,
        private readonly OrderLicenseLookupInterface $licenseLookup,
        private readonly LicensePriceService $licensePriceService,
    ) {
    }

    public function createFromOrderRequest(
        LicenseOrderDTO $request,
        string $orderType
    ): ClientLicenseOrder
    {
        $client = $this->clientLookup->findBySubdomainWithLicenseDetails($request->subdomain);
        if (!$client) {
            throw new OrderClientNotFoundException();
        }

        $license = $this->licenseLookup->findLicenseById($request->licenseId);
        if (!$license) {
            throw new LicenseNotAvailableException();
        }

        $selectedSet = null;
        if ($request->selectedSetId) {
            $selectedSet = $this->licenseLookup->findLicenseSetById($request->selectedSetId);
            if (!$selectedSet) {
                throw new LicenseSetNotAvailableException();
            }
        }

        if ($orderType === LicenseOrderTypeEnum::UPGRADE_LICENSE->value) {
            $latestClientLicense = $client->getLatestClientLicense();
            $period = $latestClientLicense->getPeriod();
            $fullMonthsToPay = $this->licensePriceService->getFullPeriodsBetweenDates(
                new \DateTime(), $latestClientLicense->getExpiredAt(), $period
            );

            if ($latestClientLicense->getLicense()->isTrial()) {
                throw new LicenseNotAvailableException();
            }

            if ($fullMonthsToPay === 0) {
                throw new OrderNoFullPeriodsException();
            }

            $isCheaper = $license->getPriceByPeriod($period) < $latestClientLicense->getLicense()->getPriceByPeriod($period);
            if ($isCheaper) {
                throw new OrderUpgradeException();
            }

            $request->period = $period;
        }

        $order = new ClientLicenseOrder(
            orderId:     ClientLicenseOrder::generateOrderId(),
            client:      $client,
            subdomain:   $request->subdomain,
            specialCode: $request->specialCode ?? null,
            license:     $license,
            selectedSet: $selectedSet,
            period:      $request->period,
            buyerData:   $request->toBuyerData(),
            orderType:   $orderType,
        );

        foreach ($request->addons as $addonId) {
            $licenseAddon = $this->licenseLookup->findAddonByIdAndLicense($addonId, $license);

            if (!$licenseAddon) {
                throw new LicenseAddonNotAvailableException();
            }

            $order->addSelectedAddon(ClientLicenseOrderAddon::forAddon($licenseAddon));
        }

        foreach ($request->additionalDevices as $additionalDeviceDTO) {
            $additionalDevice = $this->licenseLookup->findAdditionalDeviceByIdAndLicense(
                $additionalDeviceDTO->additionalDeviceId, $license
            );

            if (!$additionalDevice) {
                throw new LicenseDeviceNotAvailableException();
            }

            $order->addAdditionalDevice(
                ClientLicenseOrderDevice::forDevice($additionalDevice, $additionalDeviceDTO->quantity)
            );
        }

        $breakdown = $orderType === LicenseOrderTypeEnum::UPGRADE_LICENSE->value
            ? $this->licensePriceService->calculateUpgradePriceFromOrder($order)
            : $this->licensePriceService->calculatePriceFromOrder($order);

        if ($breakdown->totalPriceNet->getValueAsFloat() !== $request->payment->totalPriceNet) {
            throw new TotalPriceMismatchException(['correctPriceNet' => $breakdown->totalPriceNet->getValueAsFloat()]);
        }

        $order->applyPricing($breakdown);

        $order->attachPayment(Payment::initialize(
            paymentType:      $request->payment->methodType,
            paymentTypeValue: $request->payment->methodValue,
            currency:         $request->payment->currency,
            totalPriceNet:    $breakdown->totalPriceNet,
            totalPriceGross:  $this->licensePriceService->calculatePriceGrossFromNet($breakdown->totalPriceNet),
            continueUrl:      str_replace('{orderId}', $order->getOrderId(), $request->payment->continueUrl),
            order:            $order,
        ));

        return $order;
    }

    public function createFromOrderAddonsRequest(OrderAddonsDTO $request): ClientLicenseOrder
    {
        $client = $this->clientLookup->findBySubdomainWithLicenseDetails($request->subdomain);
        if (!$client) {
            throw new OrderClientNotFoundException();
        }

        $latestClientLicense = $client->getLatestClientLicense();
        $period = $latestClientLicense->getPeriod();
        $fullMonthsToPay = $this->licensePriceService->getFullPeriodsBetweenDates(
            new \DateTime(), $latestClientLicense->getExpiredAt(), $period
        );

        if ($latestClientLicense->getLicense()->isTrial()) {
            throw new LicenseNotAvailableException();
        }

        if ($fullMonthsToPay === 0) {
            throw new OrderNoFullPeriodsException();
        }

        $order = new ClientLicenseOrder(
            orderId:     ClientLicenseOrder::generateOrderId(),
            client:      $client,
            subdomain:   $request->subdomain,
            specialCode: $request->specialCode ?? null,
            license:     null,
            selectedSet: null,
            period:      $period,
            buyerData:   $request->toBuyerData(),
            orderType:   LicenseOrderTypeEnum::NEW_ADDONS->value,
        );

        foreach ($request->addons as $addonId) {
            $licenseAddon = $this->licenseLookup->findAddonByIdAndLicense($addonId, $latestClientLicense->getLicense());

            if (!$licenseAddon) {
                throw new LicenseAddonNotAvailableException();
            }

            $order->addSelectedAddon(ClientLicenseOrderAddon::forAddon($licenseAddon));
        }

        foreach ($request->additionalDevices as $additionalDeviceDTO) {
            $additionalDevice = $this->licenseLookup->findAdditionalDeviceByIdAndLicense(
                $additionalDeviceDTO->additionalDeviceId, $latestClientLicense->getLicense()
            );

            if (!$additionalDevice) {
                throw new LicenseDeviceNotAvailableException();
            }

            $order->addAdditionalDevice(
                ClientLicenseOrderDevice::forDevice($additionalDevice, $additionalDeviceDTO->quantity)
            );
        }

        $breakdown = $this->licensePriceService->calculatePriceFromAddonsOrder(
            $order, $latestClientLicense->getLicense()
        );
        if ($breakdown->totalPriceNet->getValueAsFloat() !== $request->payment->totalPriceNet) {
            throw new TotalPriceMismatchException(['correctPriceNet' => $breakdown->totalPriceNet->getValueAsFloat()]);
        }

        $order->applyAddonsPricing($breakdown);

        $order->attachPayment(Payment::initialize(
            paymentType:      $request->payment->methodType,
            paymentTypeValue: $request->payment->methodValue,
            currency:         $request->payment->currency,
            totalPriceNet:    $breakdown->totalPriceNet,
            totalPriceGross:  $this->licensePriceService->calculatePriceGrossFromNet($breakdown->totalPriceNet),
            continueUrl:      str_replace('{orderId}', $order->getOrderId(), $request->payment->continueUrl),
            order:            $order,
        ));

        return $order;
    }
}
