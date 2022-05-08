<?php

namespace App\Security;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->getPathInfo() == '/connect/facebook/check';
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return RedirectResponse|Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $url = $this->router->generate('app_login');
        return new RedirectResponse($url);
    }

    /**
     * @param Request $request
     * @return \League\OAuth2\Client\Token\AccessToken|mixed
     */
    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getFacebookClient());
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return null|object|UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->getFacebookClient()
            ->fetchUserFromToken($credentials);
        $email = $facebookUser->getEmail();

        $existingUser = $this->em->getRepository('App:User')
            ->findOneBy(['facebookId' => $facebookUser->getId()]);
        if ($existingUser) {
            return $existingUser;
        }

        $user = $this->em->getRepository('App:User')
            ->findOneBy(['email' => $email]);
        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $user->setNom($facebookUser->getFirstName());
            $user->setPrenom($facebookUser->getName());
            $user->setCin(0);
            $user->setImageProfile($facebookUser->getPictureUrl());
            $user->setPassword(md5(uniqid()));
            $user->setDatedenaissance(new DateTime("now"));
            $user->setTelephone(00);

        }
        $user->setGoogleId(" ");
        $user->setFacebookId($facebookUser->getId());
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @return OAuth2ClientInterface
     */
    private function getFacebookClient()
    {
        return $this->clientRegistry->getClient('facebook_main');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): RedirectResponse
    {
        return new RedirectResponse($this->router->generate('app_user_index'));
    }


}