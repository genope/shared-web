<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Offres;
use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\AvisType;
use App\Form\ReclamationType;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    /**
     * @Route("/avis", name="app_avis")
     */

    public function index(Request $request ,EntityManagerInterface $entityManager): Response
    {
        $avisafficher = $entityManager
            ->getRepository(Avis::class)
            ->findAll();

$avis = new Avis();
$form = $this->createForm(AvisType::class,$avis);

$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {


$finduer = $this -> getDoctrine()->getRepository(User::class)->find(9637898);



$idoffre2 = $this -> getDoctrine()->getRepository(Offres::class)->find(10);
$avis->setIdguest($finduer);
$avis->setIdoffre($idoffre2);
$avis->setDatecreation(new \DateTime());
$entityManager->persist($avis);
$entityManager->flush() ;

return $this->redirectToRoute('app_avis');
}
return $this->render('avis/index.html.twig', [
    'avis2' => $avis,'avis' => $avisafficher,
    'form' => $form->createView(),
]);}
    /**
     * @Route("/back/avis", name="app_avis_back")
     */

    public function index2(EntityManagerInterface $entityManager): Response
    {
        $aviss = $entityManager
            ->getRepository(Avis::class)
            ->findAll();
        return $this->render('avis/back.html.twig', [
            'aviss' => $aviss,
        ]);
    }


    /**
     * @Route("/Supprimer/{id}", name="app_avis_delete")
     */
    public function delete(Avis $avis, EntityManagerInterface $entityManager)
    {

        $entityManager->remove($avis);
        $entityManager->flush();


        return $this->redirectToRoute('app_avis_back');
    }
}
