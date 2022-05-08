<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Form\CommentaireType;
use App\Form\PublicationType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
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
    public function index(EntityManagerInterface $entityManager,Request $request, PaginatorInterface $paginator): Response
    {
        $publication=$this->getDoctrine()->getRepository(Publication::class)->findAll();
        $publications = $paginator->paginate(
            $publication,
            $request->query->getInt('page', 1),
            3
        );
$commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->findBy(['etat'=>'En cours de traitement']);

        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $ci = $this->getUser();
            $userRole = $this->getUser()->getRoles();

        }else {
            $userCon = 0;
            $userName = "";
            $ci = null;
            $cin = [12345678];
            $userRole = null;
        }
        return $this->render('publication/index.html.twig', [
            'publications' => $publications,'commentaires'=>$commentaire,'userCon' => $userCon,
            'userName' => $userName,
            'Usercin' =>$ci,
            'userRole' =>$userRole,
            'user'=>$cin,
        ]);
    }



    /**
     * @Route("/p/{id}", name="app_publication_show" )
     */
    public function show(Publication $publication, EntityManagerInterface $entityManager,$id, Request $request): Response
    {   $em=$this->getDoctrine()->getManager();
        $commentaires = $entityManager
        ->getRepository(Commentaire::class)
        ->findBy(['idPublication'=>$id]);
        $query=$entityManager
            ->createQuery("select count(s) from App\Entity\Commentaire s where s.idPublication=:id  ")
            ->setParameter('id',$id);
        $number=$query->getSingleScalarResult();

        $dql = "SELECT AVG(e.note) AS rating FROM App\Entity\Commentaire e "."WHERE e.idPublication = :id AND e.etat='Afficher' ";
        $rating = $em->createQuery($dql)
            ->setParameter('id', $id)
            ->getSingleScalarResult();


        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $ci = $this->getUser();
            $userRole = $this->getUser()->getRoles();

        }else {
            $userCon = 0;
            $userName = "";
            $ci = null;
            $cin = [12345678];
            $userRole = null;
        }

        return $this->render('publication/show.html.twig', [
            'publication' => $publication,'commentaires'=>$commentaires,'idpub'=>$id,'number'=>$number,'rating'=>$rating,'userCon' => $userCon,
            'userName' => $userName,
            'Usercin' =>$ci,
            'userRole' =>$userRole,
            'user'=>$cin,
        ]);
    }



    /**
     * @Route("/Supprimer/{id}", name="app_publication_delete")
     */
    public function delete(Publication $publication, EntityManagerInterface $entityManager)
    {

        $entityManager->remove($publication);
        $entityManager->flush();

        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $ci = $this->getUser();
            $userRole = $this->getUser()->getRoles();

        }else {
            $userCon = 0;
            $userName = "";
            $ci = null;
            $cin = [12345678];
            $userRole = null;
        }

        return $this->redirectToRoute('app_publication_index');
    }
    /**
     * @Route("/Supprimerbackcom/{id}_{idpub}", name="app_commentaire_back_delete")
     */
    public function deleteComment(Commentaire $commentaire, EntityManagerInterface $entityManager,$idpub)
    {

        $entityManager->remove($commentaire);
        $entityManager->flush();

        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $ci = $this->getUser();
            $userRole = $this->getUser()->getRoles();

        }else {
            $userCon = 0;
            $userName = "";
            $ci = null;
            $cin = [12345678];
            $userRole = null;
        }
        return $this->redirectToRoute('app_publication_show');
    }
    /**
     * @Route("/ajoutercom/{id}_{idpub}", name="app_commentaire_back_ajoutercom")
     */
    public function ajoutercom(Commentaire $commentaire, EntityManagerInterface $entityManager,$idpub)
    {
        $commentaire->setEtat("Afficher");
        $entityManager->flush();

        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $ci = $this->getUser();
            $userRole = $this->getUser()->getRoles();

        }else {
            $userCon = 0;
            $userName = "";
            $ci = null;
            $cin = [12345678];
            $userRole = null;
        }

        return $this->redirectToRoute('app_publication_show',['id'=>$idpub ]);
    }
    /**
     * @Route("/masquercom/{id}_{idpub}", name="app_commentaire_back_masquercom")
     */
    public function Masquercom(Commentaire $commentaire, EntityManagerInterface $entityManager ,$idpub)
    {
        $commentaire->setEtat("Afficher");
        $entityManager->flush();



        return $this->redirectToRoute('app_publication_show',['id'=>$idpub ]);
    }
    /**
     * @Route("/affichercom/{id}_{idpub}", name="app_commentaire_back_affichercom")
     */
    public function Affichercom(Commentaire $commentaire, EntityManagerInterface $entityManager,$idpub)
    {
        $commentaire->setEtat("Masquer");
        $entityManager->flush();



        return $this->redirectToRoute('app_publication_show',['id'=>$idpub ]);
    }
}