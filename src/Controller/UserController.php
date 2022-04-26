<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdateHostType;
use App\Form\UpdateType;
use App\Form\UserType;
use App\Security\LoginFormAuthenticator;
use App\Service\CaptchaValidator;
use App\Service\Mailer;
use App\Service\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/allusers", name="app_user_users", methods={"GET"})
     */
    public function listUsers(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users = $entityManager
            ->getRepository(User::class)
            ->findAll();

        return $this->render('/user/index.html.twig', [
            'users' => $users,
            'user' =>$cin,
        ]);
    }
    /**
     * @Route("/", name="app_user_index", methods={"GET"})
     */
    public function index(): Response
    {

        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $ci = $this->getUser();
        }else {
            $userCon = 0;
            $userName = "";
            $ci = null;

        }
        return $this->render('index.html.twig', [
            'userCon' => $userCon,
            'userName' => $userName,
            'Usercin' =>$ci,
        ]);
    }



    /**
     * @Route("/{cin}", name="app_user_show", methods={"GET", "POST"})
     */
    public function show(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser();
        $form = $this->createForm(UpdateType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'users' =>$cin,
        ]);
    }
    /**
     * @Route("/host/{cin}", name="app_user_show_host", methods={"GET", "POST"})
     */
    public function showHost(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UpdateHostType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/profileHost.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{cin}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{cin}", name="app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getCin(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


}
