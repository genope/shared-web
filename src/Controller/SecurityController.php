<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\CaptchaService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('app_user_index'));
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        var_dump($lastUsername);
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="register", methods={"GET", "POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, CaptchaService $captchaService, TranslatorInterface $translator): Response
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$captchaService->validateCaptcha($request->get('g-recaptcha-response'))) {
                $form->addError(new FormError($translator->trans('captcha.wrong')));
                throw new ValidatorException('captcha.wrong');
            }
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/connect/google", name="connect_google")
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function googleConnectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry->getClient('google_main')->redirect();
    }

    /**
     * @Route("/connect/google/check", name="connect_google_check")
     * @param $request Request
     */
    public function connectGoogleCheckAction(Request $request)
    {
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/connect/facebook", name="connect_facebook")
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public function facebookConnectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry->getClient('facebook_main')->redirect();
    }

    /**
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     * @param $request Request
     */
    public function connectFacebookCheckAction(Request $request)
    {
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/language/{locale}", name="language")
     * @param $request Request
     */
    public function changeLangue(Request $request)
    {
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

}
