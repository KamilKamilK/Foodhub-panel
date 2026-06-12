<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Http;

use App\ClientContext\Application\Command\Client\AddressData;
use App\ClientContext\Application\Command\Client\CompanyData;
use App\ClientContext\Application\Command\Client\PatchClientCompanyCommand;
use App\Shared\Domain\ValueObject\Subdomain;
use App\ClientContext\Application\Command\ClientUser\CreateCommand;
use App\ClientContext\Application\Command\ClientUser\DeleteCommand;
use App\ClientContext\Application\Command\ClientUser\UpdateCommand;
use App\ClientContext\Application\DTO\CreateUserRequest;
use App\ClientContext\Application\DTO\DeleteUserRequest;
use App\ClientContext\Application\DTO\PatchClientCompanyRequest;
use App\ClientContext\Application\DTO\UpdateUserRequest;
use App\Shared\Domain\Exception\ConstraintValidatorException;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClientUserController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function postAction(Request $request): JsonResponse
    {
        $dto = $this->deserialize($request, CreateUserRequest::class, ['userCreate']);
        $this->validate($dto);

        $this->dispatch(new CreateCommand($dto));
        return new JsonResponse(null, Response::HTTP_CREATED);
    }

    public function putAction(Request $request): JsonResponse
    {
        $dto = $this->deserialize($request, UpdateUserRequest::class, ['userUpdate']);
        $this->validate($dto);

        $this->dispatch(new UpdateCommand($dto));
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    public function patchCompanyAction(Request $request): JsonResponse
    {
        $dto = $this->deserialize($request, PatchClientCompanyRequest::class, ['userUpdate']);
        $this->validate($dto);

        $this->dispatch(new PatchClientCompanyCommand(
            Subdomain::fromString($dto->subDomain ?? ''),
            new CompanyData($dto->name, $dto->shortName, $dto->taxIdNumber, $dto->registrationNumber),
            new AddressData($dto->street, $dto->buildingNo, $dto->localNo, $dto->zipCode, $dto->city, $dto->country),
        ));
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    public function deleteAction(Request $request): JsonResponse
    {
        $dto = $this->deserialize($request, DeleteUserRequest::class, ['userDelete']);
        $this->validate($dto);

        $this->dispatch(new DeleteCommand($dto));
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    private function dispatch(object $message): mixed
    {
        return $this->messageBus->dispatch($message)->last(HandledStamp::class)?->getResult();
    }

    /** @param string[] $groups */
    private function deserialize(Request $request, string $type, array $groups = []): object
    {
        $ctx = DeserializationContext::create();
        if ($groups) {
            $ctx->setGroups($groups);
        }
        $obj = $this->serializer->deserialize((string) $request->getContent(), $type, 'json', $ctx);
        if (!$obj instanceof $type) {
            throw new \UnexpectedValueException("Deserialization failed for $type");
        }
        return $obj;
    }

    private function validate(object $dto, array $groups = []): void
    {
        $violations = $this->validator->validate($dto, null, $groups ?: null);
        if ($violations->count()) {
            throw new ConstraintValidatorException($violations);
        }
    }
}
