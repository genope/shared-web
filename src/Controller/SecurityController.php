<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\HostType;
use App\Form\ResetPassType;
use App\Form\UserType;
use App\Repository\UserRepo;
use App\Security\LoginFormAuthenticator;
use App\Service\CaptchaService;
use App\Service\Mailer;
use App\Service\TokenGenerator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use PhpParser\Node\Stmt\Echo_;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
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
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/loginPage.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register/guest", name="register", methods={"GET","POST"})
     */
    public function register(Request                      $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             CaptchaService               $captchaService,
                             TranslatorInterface          $translator,
                             GuardAuthenticatorHandler    $guardHandler,
                             LoginFormAuthenticator       $authenticator,
                             \Swift_Mailer                $mailer
    ): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {


           /* if (!$captchaService->validateCaptcha($request->get('g-recaptcha-response'))) {
                $form->addError(new FormError($translator->trans('captcha.wrong')));
                throw new ValidatorException('captcha.wrong');
            }*/
            $user->setActivationToken(md5(uniqid()));
            $user->setRoles(array('ROLE_GUEST'));
            $user->setEtat(array('Approved'));
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();
            $entityManager->clear();

            $message = (new \Swift_Message('Nouveau compte'))
                ->setFrom('kiraamv1337@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/activation.html.twig', ['token' => $user->getActivationToken()]
                    ),
                    'text/html'
                );
            $mailer->send($message);


            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main'
            );

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register/host", name="registerHost", methods={"GET", "POST"})
     */
    public
    function registerHost(Request                      $request,
                          UserPasswordEncoderInterface $passwordEncoder,
                          CaptchaService               $captchaService,
                          TranslatorInterface          $translator,
                          GuardAuthenticatorHandler    $guardHandler,
                          LoginFormAuthenticator       $authenticator,
                          \Swift_Mailer                $mailer): Response
    {

        $user = new User();
        $form = $this->createForm(HostType::class, $user);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            /*   if (!$captchaService->validateCaptcha($request->get('g-recaptcha-response'))) {
                   $form->addError(new FormError($translator->trans('captcha.wrong')));
                   throw new ValidatorException('captcha.wrong');
               }*/
            $user->setRoles(array('ROLE_HOST'));
            $user->setEtat(array('Approved'));
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $entityManager->persist($user);
            $entityManager->flush();

            $message = (new \Swift_Message('Nouveau compte'))
                ->setFrom('TNSharedInc@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/activation.html.twig', ['token' => $user->getActivationToken()]
                    ),
                    'text/html'
                );
            $mailer->send($message);
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main'
            );

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('user/registerHost.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activation/{token}", name="activation")
     */
    public
    function activation($token, UserRepo $respo)
    {
        $user = $respo->findOneby(['activation_token' => $token]);
        if (!$user) {
            throw $this->createNotFoundException('404 user not found');
        }
        $user->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        $entityManager->clear();
        $this->addFlash('message', 'your account is fully activated');
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/connect/google", name="connect_google")
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public
    function googleConnectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry->getClient('google_main')->redirect();
    }

    /**
     * @Route("/connect/google/check", name="connect_google_check")
     * @param $request Request
     */
    public
    function connectGoogleCheckAction(Request $request)
    {
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/connect/facebook", name="connect_facebook")
     * @param ClientRegistry $clientRegistry
     * @return Response
     */
    public
    function facebookConnectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry->getClient('facebook_main')->redirect();
    }

    /**
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     * @param $request Request
     */
    public
    function connectFacebookCheckAction(Request $request)
    {
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/forget_password", name="app_forgotten_password")
     */
    public function ForgetPassword(Request $request, UserRepo $user, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator
    ): Response
    {

        $form = $this->createForm(ResetPassType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $donnees = $form->getData();
            $user = $user->findOneByEmail($donnees['email']);
            if ($user === null) {

                $this->addFlash('danger', 'Cette adresse e-mail est inconnue');
                return $this->redirectToRoute('app_login');
            }
            $token = $tokenGenerator->generateToken();

            try {
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Forget password'))
                ->setFrom('TNSharedInc@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    "Bonjour,<br><br>Une demande de réinitialisation de mot de passe a été effectuée pour le site Nouvelle-Techno.fr. Veuillez cliquer sur le lien suivant : " . $url,
                    'text/html'
                );


            $mailer->send($message);
            $this->addFlash('message', 'E-mail de réinitialisation du mot de passe envoyé !');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('Security/forgot-password.html.twig',
            ['emailForm' => $form->createView()]);
    }

    /**
     * @Route("/reset_pass/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);
        if ($user === null) {
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('app_login');
        }
        if ($request->isMethod('POST')) {

            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash('message', 'Mot de passe mis à jour');
            return $this->redirectToRoute('app_login');
        } else {
            return $this->render('security/reset-password.html.twig', ['token' => $token]);
        }

    }


    /**
     * @Route("/change_locale/{locale}", name="change_locale")
     */
    public function changeLocale($locale, Request $request)
    {
        $request->getSession()->set('_locale', $locale);

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user->setLocale($locale);
        $em->persist($user);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));

    }
}
