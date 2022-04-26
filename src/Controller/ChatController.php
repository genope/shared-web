<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Form\ChatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/chat")
 */
class ChatController extends AbstractController
{
    /**
     * @Route("/", name="app_chat_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $chats = $entityManager
            ->getRepository(Chat::class)
            ->findAll();

        return $this->render('chat/index.html.twig', [
            'chats' => $chats,
        ]);
    }

    /**
     * @Route("/new", name="app_chat_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser();

        $sender=$this->getUser()->getCin();

        $chats = $entityManager
            ->getRepository(Chat::class)
            ->findAll();
intval($sender);

        $chat = new Chat();
        $chat->setIdSender($cin);
        $form = $this->createForm(ChatType::class, $chat);
        $form->handleRequest($request);




        if ($form->isSubmitted() && $form->isValid()) {
            $time = new \DateTime();

            $chat->setEnvoyeat($time);
            $entityManager->persist($chat);
            $entityManager->flush();

            return $this->redirectToRoute('app_chat_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chat/new.html.twig', [
            'chats' => $chats,
            'chat' => $chat,
            'form' => $form->createView(),
            'user' => $cin,
            'sender'=>$sender,
        ]);
    }

    /**
     * @Route("/{idChat}", name="app_chat_show", methods={"GET"})
     */
    public function show(Chat $chat): Response
    {
        return $this->render('chat/show.html.twig', [
            'chat' => $chat,
        ]);
    }

    /**
     * @Route("/{idChat}/edit", name="app_chat_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Chat $chat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChatType::class, $chat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chat/edit.html.twig', [
            'chat' => $chat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idChat}", name="app_chat_delete", methods={"POST"})
     */
    public function delete(Request $request, Chat $chat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chat->getIdChat(), $request->request->get('_token'))) {
            $entityManager->remove($chat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chat_new', [], Response::HTTP_SEE_OTHER);
    }
}
