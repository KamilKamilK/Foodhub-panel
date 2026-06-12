<?php declare(strict_types=1);

namespace App\OrderContext\Infrastructure\Http;

use App\OrderContext\Application\Command\PayUWebhook\PayUWebhookCommand;
use App\OrderContext\Application\Query\GetPayUPaymentsData\GetPayUPaymentsDataQuery;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Payments')]
class PayUController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/api/payu/data', name: 'payu_payments_data', methods: ['GET'])]
    public function getPayUPaymentsData(): JsonResponse
    {
        $result = $this->messageBus->dispatch(new GetPayUPaymentsDataQuery())
            ->last(HandledStamp::class)?->getResult();

        $ctx = SerializationContext::create()->setGroups(['payuData'])->enableMaxDepthChecks();
        return JsonResponse::fromJsonString($this->serializer->serialize($result, 'json', $ctx));
    }

    #[Route('/webhook/payment/payu', name: 'payments_webhook_payu', methods: ['POST'])]
    public function webhookPayU(Request $request): JsonResponse
    {
        $this->messageBus->dispatch(new PayUWebhookCommand($request->getContent()));

        return new JsonResponse(null, 200);
    }
}
