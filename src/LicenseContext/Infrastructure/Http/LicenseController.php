<?php declare(strict_types=1);

namespace App\LicenseContext\Infrastructure\Http;

use App\LicenseContext\Application\Command\LicenseContactCommand\LicenseContactCommand;
use App\LicenseContext\Application\Query\GetList\GetListQuery;
use App\Shared\Domain\ValueObject\Subdomain;
use App\LicenseContext\Application\Query\GetSetList\GetSetListQuery;
use App\Shared\Domain\Exception\ConstraintValidatorException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'License')]
class LicenseController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('/api/license', name: 'get_license_list', methods: ['GET'])]
    public function getLicenseList(): JsonResponse
    {
        return $this->jmsJson($this->dispatch(new GetListQuery()), ['licenseGet']);
    }

    #[Route('/api/license/set', name: 'get_license_set_list', methods: ['GET'])]
    public function getLicenseSetList(): JsonResponse
    {
        return $this->jmsJson($this->dispatch(new GetSetListQuery()), ['licenseGet']);
    }

    #[Route('/api/license/contact', name: 'send_contact_request', methods: ['POST'])]
    public function sendContactRequest(Request $request): Response
    {
        $dto = $this->serializer->deserialize((string) $request->getContent(), \App\LicenseContext\Application\DTO\License\ContactLicenseDTO::class, 'json');
        $this->validate($dto);
        $this->dispatch(new LicenseContactCommand(
            Subdomain::fromString($dto->subdomain ?? ''),
            $dto->contactPhone ?? '',
        ));

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    private function dispatch(object $message): mixed
    {
        return $this->messageBus->dispatch($message)->last(HandledStamp::class)?->getResult();
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
