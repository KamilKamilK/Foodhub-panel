<?php declare(strict_types=1);

namespace App\OrderContext\Infrastructure\Http;

use App\OrderContext\Application\Command\OrderAddonsCommand\OrderAddonsCommand;
use App\OrderContext\Application\Command\OrderCommand\OrderCommand;
use App\OrderContext\Application\Command\OrderUpgradeLicenseCommand\OrderUpgradeLicenseCommand;
use App\OrderContext\Application\Query\GetCompletedOrder\GetCompletedOrderQuery;
use App\OrderContext\Application\Query\GetInvoiceList\GetInvoiceListQuery;
use App\Shared\Domain\ValueObject\Subdomain;
use App\OrderContext\Application\Query\GetOrder\GetOrderQuery;
use App\OrderContext\Application\DTO\LicenseOrder\LicenseOrderDTO;
use App\OrderContext\Application\DTO\LicenseOrder\OrderAddonsDTO;
use App\OrderContext\Application\DTO\LicenseOrder\GenerateResponse;
use App\Shared\Infrastructure\Service\PdfService;
use App\Shared\Domain\Exception\ConstraintValidatorException;
use App\Shared\Domain\ValueObject\Decimal;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use NumberToWords\NumberToWords;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'License')]
class LicenseOrderController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly PdfService $pdf,
    ) {
    }

    #[Route('/api/license/order', name: 'order_license', methods: ['POST'])]
    public function orderLicense(Request $request): JsonResponse
    {
        $dto = $this->deserialize($request->getContent(), LicenseOrderDTO::class);
        $this->validate($dto);

        $orderId = $this->dispatch(new OrderCommand($dto));
        return $this->jmsJson($this->dispatch(new GetOrderQuery($orderId)), ['licenseOrder']);
    }

    #[Route('/api/license/order/addons', name: 'update_order_license_addons', methods: ['POST'])]
    public function updateOrderLicenseAddons(Request $request): JsonResponse
    {
        $dto = $this->deserialize($request->getContent(), OrderAddonsDTO::class);
        $this->validate($dto);

        $orderId = $this->dispatch(new OrderAddonsCommand($dto));
        return $this->jmsJson($this->dispatch(new GetOrderQuery($orderId)), ['licenseOrder']);
    }

    #[Route('/api/license/order/upgrade', name: 'upgrade_order_license', methods: ['POST'])]
    public function upgradeOrderLicense(Request $request): JsonResponse
    {
        $dto = $this->deserialize($request->getContent(), LicenseOrderDTO::class);
        $this->validate($dto);

        $orderId = $this->dispatch(new OrderUpgradeLicenseCommand($dto));
        return $this->jmsJson($this->dispatch(new GetOrderQuery($orderId)), ['licenseOrder']);
    }

    #[Route('/api/license/order/invoice', name: 'get_order_invoices_list', methods: ['GET'])]
    public function getOrderInvoicesList(Request $request): JsonResponse
    {
        return $this->jmsJson(
            $this->dispatch(new GetInvoiceListQuery(Subdomain::fromString($request->query->getString('subdomain')))),
            ['licenseOrderInvoice'],
        );
    }

    #[Route('/api/license/order/invoice/{orderId}', name: 'get_order_invoice', methods: ['GET'])]
    public function getOrderInvoice(Request $request, string $orderId): JsonResponse
    {
        $order = $this->dispatch(new GetCompletedOrderQuery($orderId));

        $amount = (int) $order->getPayment()->getTotalPriceGross()
            ->mul(new Decimal('100'))->getValueAsFloat();

        $renderedView = $this->renderView('invoice/invoice.html.twig', [
            'order' => $order,
            'inWords' => (new NumberToWords())
                ->getCurrencyTransformer($request->getLocale())
                ->toWords($amount, $order->getPayment()->getCurrency()),
        ]);

        $pdfUrl = $this->pdf->generateFromView($renderedView, 'invoice_');

        return $this->jmsJson(new GenerateResponse($pdfUrl), ['generateResponse']);
    }

    #[Route('/api/license/order/{orderId}', name: 'get_order_license', methods: ['GET'])]
    public function getOrderLicense(string $orderId): JsonResponse
    {
        return $this->jmsJson($this->dispatch(new GetOrderQuery($orderId)), ['licenseOrder']);
    }

    private function dispatch(object $message): mixed
    {
        return $this->messageBus->dispatch($message)->last(HandledStamp::class)?->getResult();
    }

    private function deserialize(string $content, string $type): object
    {
        $obj = $this->serializer->deserialize($content, $type, 'json');
        if (!$obj instanceof $type) {
            throw new \UnexpectedValueException("Deserialization failed for $type");
        }
        return $obj;
    }

    private function validate(object $dto): void
    {
        $violations = $this->validator->validate($dto);
        if ($violations->count()) {
            throw new ConstraintValidatorException($violations);
        }
    }

    /** @param string[] $groups */
    private function jmsJson(mixed $data, array $groups, int $status = 200): JsonResponse
    {
        $ctx = SerializationContext::create()->setGroups($groups)->enableMaxDepthChecks();
        return JsonResponse::fromJsonString($this->serializer->serialize($data, 'json', $ctx), $status);
    }
}
