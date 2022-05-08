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




}