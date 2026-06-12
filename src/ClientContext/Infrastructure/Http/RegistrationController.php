<?php declare(strict_types=1);

namespace App\ClientContext\Infrastructure\Http;

use App\ClientContext\Application\Command\ConfirmAccount\ConfirmAccountCommand;
use App\ClientContext\Application\Query\GetAgreements\GetAgreementListQuery;
use App\ClientContext\Application\Command\ResendMail\ResendMailCommand;
use App\ClientContext\Application\Command\Setup\DTO\User;
use App\ClientContext\Application\Command\Setup\SetupCommand;
use App\ClientContext\Application\Query\GetSetupResult\GetSetupResultQuery;
use App\Shared\Application\Query\GetPlacesTypes\GetPlacesTypesQuery;
use App\ClientContext\Application\DTO\RegistrationRequest;
use App\Shared\Domain\Exception\AppException;
use App\Shared\Infrastructure\Form\PlacesType;
use App\ClientContext\Infrastructure\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly TranslatorInterface $translator,
        private readonly string $appUrl,
    ) {
    }

    public function registerFirstStep(Request $request): Response
    {
        $placesTypes = $this->dispatch(new GetPlacesTypesQuery($request->getLocale()));
        $form = $this->createForm(PlacesType::class);

        if ($this->handleFormSubmission($form, $request)) {
            return $this->redirectToRoute('lsi_register_second', $form->getData());
        }

        return $this->render('registration/register_first_step.html.twig', [
            'form' => $form->createView(),
            'types' => $placesTypes,
        ]);
    }

    public function registerSecondStep(Request $request, string $type): Response
    {
        $agreements = $this->dispatch(new GetAgreementListQuery(strtolower($request->getLocale())));
        $form = $this->createForm(RegistrationType::class, new RegistrationRequest(), ['agreements' => $agreements]);
        $form->get('type')->setData($type);

        if ($request->query->has('withProducts')) {
            $form->get('withProducts')->setData($request->query->getBoolean('withProducts'));
        }

        $installationResult = null;
        if ($this->handleFormSubmission($form, $request)) {
            $installationResult = $this->handleSetup($form->getData(), $request);
        }

        return $this->render('registration/register_second_step.html.twig', [
            'form' => $form->createView(),
            'type' => $type,
            'agreements' => $agreements,
            'installationResult' => $installationResult,
            'success' => (bool) $installationResult,
        ]);
    }

    public function confirm(Request $request): Response
    {
        try {
            $confirmationToken = $request->query->get('confirmationToken', '');
            $resultUrl = $request->query->get('resultUrl', '');
            $resultUrl = ($resultUrl && str_contains($resultUrl, 'email')) ? $resultUrl : $this->appUrl;
            $this->dispatch(new ConfirmAccountCommand($confirmationToken, $request->getLocale()));
            return new RedirectResponse(urldecode($resultUrl));
        } catch (AppException $e) {
            $this->addFlash('error', $this->translator->trans('exception.' . $e->getAppCode(), [], 'exception'));
        }

        return $this->render('registration/confirm_error.html.twig', ['appUrl' => $this->appUrl]);
    }

    public function resendMail(Request $request): Response
    {
        try {
            $email = str_replace(' ', '+', $request->query->get('email', ''));
            $this->dispatch(new ResendMailCommand($email, $request->getLocale()));
        } catch (AppException $e) {
            $this->addFlash('error', $this->translator->trans('exception.' . $e->getAppCode(), [], 'exception'));
        }

        return $this->render('registration/register_resend_mail.html.twig', [
            'installationResult' => $request->query->get('installationResult'),
        ]);
    }

    private function dispatch(object $message): mixed
    {
        return $this->messageBus->dispatch($message)->last(HandledStamp::class)?->getResult();
    }

    private function handleFormSubmission(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', $this->translator->trans('registration.process.error_form'));
            return false;
        }
        return $form->isSubmitted() && $form->isValid();
    }

    private function handleSetup(RegistrationRequest $dto, Request $request): ?string
    {
        try {
            $userDto = $dto->user;
            $user = new User(
                $userDto?->name ?? '',
                $userDto?->surname ?? '',
                $userDto?->phone ?? '',
                $userDto?->email ?? '',
                $userDto?->password ?? '',
                $userDto?->specialCode ?? null,
            );
            $this->dispatch(new SetupCommand(
                $user,
                null,
                $dto->type ?? '',
                $dto->agreementIds,
                $request->getLocale(),
                $dto->withProducts,
            ));
            $setupResult = $this->dispatch(new GetSetupResultQuery($userDto?->email ?? ''));
            $this->addFlash('success', $this->translator->trans('registration.confirmation.success'));
            return $setupResult?->getConfirmationUrl();
        } catch (AppException $e) {
            $this->addFlash('error', $this->translator->trans('exception.' . $e->getAppCode(), [], 'exception'));
        } catch (\Exception) {
            $this->addFlash('error', $this->translator->trans('registration.process.error'));
        }
        return null;
    }
}
