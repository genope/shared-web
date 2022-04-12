<?php

namespace App\Controller;

use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireController extends AbstractController
{

    /**
     * @Route("/frontcommentaire{id}", name="app_front_commentaire")
     */
    public function index(EntityManagerInterface $entityManager,$id): Response
    {
        $commentaires = $entityManager
            ->getRepository(Commentaire::class)
            ->findBy(['id_publication'=>$id]);

        return $this->render('publication/show_front.html.twig', [
            'commentaires' => $commentaires,
        ]);
    }

}
