<?php declare(strict_types=1);

namespace App\MerchantContext\Infrastructure\Http;

use App\ClientContext\Application\Query\GetSpecialCodeAvailability\GetSpecialCodeAvailabilityQuery;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Merchant')]
class MerchantController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/api/merchant/specialCodeAvailability', name: 'get_special_code_availability', methods: ['GET'])]
    public function getSpecialCodeAvailability(Request $request): JsonResponse
    {
        $result = $this->messageBus->dispatch(
            new GetSpecialCodeAvailabilityQuery($request->query->getString('specialCode'))
        )->last(HandledStamp::class)?->getResult();

        $ctx = SerializationContext::create()->setGroups(['merchantGet'])->enableMaxDepthChecks();
        return JsonResponse::fromJsonString($this->serializer->serialize($result, 'json', $ctx));
    }
}
