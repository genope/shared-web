<?php

namespace App\Controller;



use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RechercheController extends AbstractController {

    /**
     * @Route("/Publication/recherchePub", name="app_front_pub_recherche")
     */
    public function index(EntityManagerInterface $em){

        if ( (isset($_GET['rech'])) && ($_GET['rech'] != null) ) {
     $query = $em -> createQuery("SELECT p from App\Entity\Publication p where p.nom LIKE :rech OR p.description LIKE :rech OR p.adresse LIKE :rech ")
         ->setParameter('rech','%'.$_GET['rech'].'%');
     $rech = $query -> getResult();
    $count = count($rech);
    $mot = $_GET['rech'];
   return $this->render("publication/recherche.html.twig",[
       'publications'=> $rech, 'count'=>$count , 'mot'=> $mot,

   ]);
        }else {

            return new Response("not found");
        }
    }
    /**
     * @Route("/Reclamation/rechercheRec", name="app_front_rec_recherche")
     */
    public function index1(EntityManagerInterface $em){

        if ( (isset($_GET['rech'])) && ($_GET['rech'] != null) ) {
            $query = $em -> createQuery("SELECT r from App\Entity\Reclamation r where r.nom LIKE :rech OR r.description LIKE :rech OR r.objet LIKE :rech ")
                ->setParameter('rech','%'.$_GET['rech'].'%');
            $rech = $query -> getResult();
            $count = count($rech);
            $mot = $_GET['rech'];
            return $this->render("reclamation/recherche.html.twig",[
                'reclamations'=> $rech, 'count'=>$count , 'mot'=> $mot,

            ]);
        }else {

            return new Response("not found");
        }
    }




}