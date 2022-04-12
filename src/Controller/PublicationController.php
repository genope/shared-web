<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Form\CommentaireType;
use App\Form\PublicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PublicationController extends AbstractController
{
    /**
     * @Route("/BackAffichage", name="app_publication_index")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $publications = $entityManager
            ->getRepository(Publication::class)
            ->findAll();

        return $this->render('publication/index.html.twig', [
            'publications' => $publications,
        ]);
    }



    /**
     * @Route("/{id}", name="app_publication_show")
     */
    public function show(Publication $publication, EntityManagerInterface $entityManager,$id, Request $request): Response
    { $commentaires = $entityManager
        ->getRepository(Commentaire::class)
        ->findBy(['idPublication'=>$id]);
        $query=$entityManager
            ->createQuery("select count(s) from App\Entity\Commentaire s where s.idPublication=:id  ")
            ->setParameter('id',$id);
        $number=$query->getSingleScalarResult();



        return $this->render('publication/show.html.twig', [
            'publication' => $publication,'commentaires'=>$commentaires,'idpub'=>$id,'number'=>$number,
        ]);
    }



    /**
     * @Route("/Supprimer/{id}", name="app_publication_delete")
     */
    public function delete(Publication $publication, EntityManagerInterface $entityManager)
    {

        $entityManager->remove($publication);
        $entityManager->flush();


        return $this->redirectToRoute('app_publication_index');
    }
    /**
     * @Route("/Supprimerbackcom/{id}_{idpub}", name="app_commentaire_back_delete")
     */
    public function deleteComment(Commentaire $commentaire, EntityManagerInterface $entityManager,$idpub)
    {

        $entityManager->remove($commentaire);
        $entityManager->flush();


        return $this->redirectToRoute('app_publication_show',array('id'=>$idpub));
    }
}