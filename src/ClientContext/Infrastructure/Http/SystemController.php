<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Http;

use App\ClientContext\Application\Command\ConfirmAccount\ConfirmAccountCommand;
use App\ClientContext\Application\Command\ResendMail\ResendMailCommand;
use App\ClientContext\Application\Command\Setup\DTO\Device;
use App\ClientContext\Application\Command\Setup\DTO\User;
use App\ClientContext\Application\Command\Setup\SetupCommand;
use App\ClientContext\Application\Query\GetAgreements\GetAgreementListQuery;
use App\ClientContext\Application\Query\GetSetupResult\GetSetupResultQuery;
use App\ClientContext\Application\Query\GetUrlByEmail\GetUrlByEmailQuery;
use App\Shared\Application\Query\GetPlacesTypes\GetPlacesTypesQuery;
use App\Shared\Application\Service\ApkVersionService;
use App\Shared\Domain\Exception\Update\ApkVersionIsDelightedException;
use App\Shared\Domain\Exception\Update\ApkVersionIsUpToDateException;
use App\Shared\Domain\Exception\Utils\DirNotFoundException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Attribute\Route;

class SystemController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly SerializerInterface $serializer,
        private readonly string $appUrl,
        private readonly string $apkFilesDir,
    ) {
    }

    #[Route('/fhp/setup', methods: ['POST'])]
    public function setup(Request $request): JsonResponse
    {
        $body = json_decode((string) $request->getContent());

        $user = new User(
            $body->user->name ?? '',
            $body->user->surname ?? '',
            $body->user->phone ?? '',
            $body->user->email ?? '',
            $body->user->password ?? '',
            $body->user->specialCode ?? null,
        );

        $device = isset($body->device) ? new Device(
            $body->device->macAddress ?? '',
            $body->device->name ?? '',
            $body->device->model ?? '',
            $body->device->platform ?? null,
            $body->device->version ?? null,
        ) : null;

        $this->messageBus->dispatch(new SetupCommand(
            $user,
            $device,
            $body->type ?? '',
            $body->agreementIds ?? [],
            $request->getLocale(),
            $body->withProducts ?? true,
        ));

        $setupResult = $this->messageBus->dispatch(
            new GetSetupResultQuery($body->user->email ?? '')
        )->last(HandledStamp::class)?->getResult();

        return new JsonResponse($setupResult?->toArray(), 201);
    }

    #[Route('/url', methods: ['GET'])]
    public function getUrlByEmail(Request $request): JsonResponse
    {
        $email = str_replace(' ', '+', $request->query->getString('email'));
        $result = $this->messageBus->dispatch(new GetUrlByEmailQuery($email))
            ->last(HandledStamp::class)?->getResult();

        return new JsonResponse([
            'url' => $result['url'] ?? null,
            'active' => $result['active'] ?? null,
            'secondMailSent' => $result['secondMailSent'] ?? null,
        ]);
    }

    #[Route('/resend', methods: ['POST'])]
    public function resendMail(Request $request): JsonResponse
    {
        $email = str_replace(' ', '+', $request->query->getString('email', ''));
        $this->messageBus->dispatch(new ResendMailCommand($email, $request->getLocale()));

        return new JsonResponse(null, 201);
    }

    #[Route('/placesTypes', methods: ['GET'])]
    public function getPlacesTypes(Request $request): JsonResponse
    {
        $result = $this->messageBus->dispatch(new GetPlacesTypesQuery($request->getLocale()))
            ->last(HandledStamp::class)?->getResult();

        return JsonResponse::fromJsonString($this->serializer->serialize($result, 'json'));
    }

    #[Route('/client/user/confirm', methods: ['POST'])]
    public function confirmAccount(Request $request): JsonResponse
    {
        $body = json_decode((string) $request->getContent());
        $this->messageBus->dispatch(new ConfirmAccountCommand(
            confirmationToken: $body->confirmationToken ?? '',
            locale:            $request->getLocale(),
        ));

        return new JsonResponse(null, 204);
    }

    #[Route('/agreements', methods: ['GET'])]
    public function getAgreements(Request $request): JsonResponse
    {
        $result = $this->messageBus->dispatch(new GetAgreementListQuery($request->getLocale()))
            ->last(HandledStamp::class)?->getResult();

        $ctx = SerializationContext::create()->setGroups(['agreementCget']);
        return JsonResponse::fromJsonString($this->serializer->serialize($result, 'json', $ctx));
    }

    #[Route('/status', methods: ['GET'])]
    public function getStatus(): JsonResponse
    {
        return new JsonResponse(['status' => 'online']);
    }

    #[Route('/apk', methods: ['GET'])]
    public function getApk(Request $request, ApkVersionService $apkVersionService): JsonResponse
    {
        $apkVersion = $request->query->getString('apk_version');

        if (!$apkVersionService->setApkDir($this->apkFilesDir)) {
            throw new DirNotFoundException();
        }
        if (!$apkVersionService->setLatestApkVersion() || !$apkVersionService->isLatestApkValid()) {
            throw new ApkVersionIsDelightedException();
        }
        if ($apkVersionService->isApkUpToDate($apkVersion)) {
            throw new ApkVersionIsUpToDateException();
        }

        return new JsonResponse([
            'apk_decode_64' => $apkVersionService->getLatestApkDecoded(),
            'apk_version'   => $apkVersionService->getLatestApkVersion(),
        ]);
    }
}
