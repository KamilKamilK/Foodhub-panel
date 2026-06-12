<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Http;

use App\ClientContext\Application\Command\UpdateAddons\AddonUpdate;
use App\ClientContext\Application\Command\UpdateAddons\DeviceUpdate;
use App\ClientContext\Application\Command\UpdateAddons\UpdateAddonsCommand;
use App\ClientContext\Application\Query\GetLicenseDetailsBySubDomain\GetLicenseDetailsBySubDomainQuery;
use App\ClientContext\Application\DTO\License\UpdateLicenseDTO;
use App\Shared\Domain\Exception\ConstraintValidatorException;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'License')]
class ClientLicenseController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('/api/license/addons', name: 'update_license_addons', methods: ['PUT'])]
    public function updateLicenseAddons(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize((string) $request->getContent(), UpdateLicenseDTO::class, 'json');
        $this->validate($dto);

        $addonUpdates = array_map(
            fn($a) => new AddonUpdate($a->addonType, $a->addonCategory, $a->isActiveOnNextPeriod),
            $dto->addons,
        );
        $deviceUpdates = array_map(
            fn($d) => new DeviceUpdate($d->deviceType, $d->quantity),
            $dto->additionalDevices,
        );

        $this->dispatch(new UpdateAddonsCommand(Subdomain::fromString($dto->subdomain ?? ''), $addonUpdates, $deviceUpdates));

        return $this->jmsJson(
            $this->dispatch(new GetLicenseDetailsBySubDomainQuery(Subdomain::fromString($dto->subdomain ?? ''), $request->getLocale())),
            ['licenseDetails', 'licenseUpdate'],
        );
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
