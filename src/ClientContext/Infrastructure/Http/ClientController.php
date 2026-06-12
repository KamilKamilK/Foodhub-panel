<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Http;

use App\ClientContext\Application\Query\GetLicenseDetailsBySubDomain\GetLicenseDetailsBySubDomainQuery;
use App\Shared\Domain\ValueObject\Subdomain;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag(name: 'Client')]
class ClientController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/client/licenseDetails', name: 'lsi_get_license_details', methods: ['GET'])]
    #[OA\Get(summary: 'Get license details by subdomain')]
    public function getLicenseDetailsBySubDomain(Request $request): JsonResponse
    {
        $result = $this->dispatch(new GetLicenseDetailsBySubDomainQuery(
            Subdomain::fromString($request->query->getString('subdomain')),
            $request->getLocale(),
        ));

        return $this->jmsJson($result, ['licenseDetails']);
    }

    private function dispatch(object $message): mixed
    {
        $envelope = $this->messageBus->dispatch($message);
        return $envelope->last(HandledStamp::class)?->getResult();
    }

    /** @param string[] $groups */
    private function jmsJson(mixed $data, array $groups, int $status = 200): JsonResponse
    {
        $ctx = SerializationContext::create()->setGroups($groups)->enableMaxDepthChecks();
        return JsonResponse::fromJsonString($this->serializer->serialize($data, 'json', $ctx), $status);
    }
}
