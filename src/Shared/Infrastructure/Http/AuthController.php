<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Http;

use App\Shared\Infrastructure\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/panel/auth')]
class AuthController extends AbstractController
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    #[Route('', name: 'auth', methods: ['GET'])]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error !== null) {
            $this->addFlash('error', $this->translator->trans('router.auth.index.form.failed'));
        }

        return $this->render('auth/index.html.twig', [
            'form' => $this->createForm(LoginType::class)->createView(),
        ]);
    }

    #[Route('/login', name: 'auth_login', methods: ['POST'])]
    public function login(): void
    {
    }
}
