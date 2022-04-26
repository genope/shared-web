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
use mofodojodino\ProfanityFilter\Check;

class AvisController extends AbstractController
{
    /**
     * @Route("/avis", name="app_avis")
     */

    public function index(Request $request ,EntityManagerInterface $entityManager): Response
    {
        $query= $entityManager->createQuery('Select avg(a.note) FROM App\Entity\Avis a');
        $moyenne=$query->getSingleScalarResult();
        $moyenne=round($moyenne,2);
        $query= $entityManager->createQuery('Select count(a.note) FROM App\Entity\Avis a');
        $nbr=$query->getSingleScalarResult();
        $avisafficher = $entityManager
            ->getRepository(Avis::class)
            ->findAll();

        $check = new Check( '../config/profanities.php');
$avis = new Avis();

$form = $this->createForm(AvisType::class,$avis);

$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    $verifier = $form['commentaire']->getData();
    $hasProfanity = $check->hasProfanity($verifier);
    if ($hasProfanity == false) {

$finduer = $this -> getDoctrine()->getRepository(User::class)->find(9637898);
$idoffre2 = $this -> getDoctrine()->getRepository(Offres::class)->find(10);
$avis->setIdguest($finduer);
$avis->setIdoffre($idoffre2);
$avis->setDatecreation(new \DateTime());
$entityManager->persist($avis);
$entityManager->flush() ;
        $good="good";
return $this->redirectToRoute('app_avis',[
    "good"=>$good
]);
    }else {
        $bad="bad";
        return $this->redirectToRoute('app_avis',[
            "bad"=>$bad
        ]);
    }
}

return $this->render('avis/index.html.twig', [
    'avis2' => $avis,'avis' => $avisafficher,'moyenne' => $moyenne,'nbr' => $nbr,
    'form' => $form->createView(),
]);}
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');

        $entities =  $em->getRepository(Reclamation::class)->findEntitiesByString($requestString);

        if(!$entities) {
            $result['entities']['error'] = "keine Einträge gefunden";
        } else {
            $result['entities'] = $this->getRealEntities($entities);
        }

        return new Response(json_encode($result));
    }

    public function getRealEntities($entities){

        foreach ($entities as $entity){
            $realEntities[$entity->getId()] = $entity->getFoo();
        }

        return $realEntities;
    }
    /**
     * @Route("/back/avis", name="app_avis_back")
     */

    public function index2(EntityManagerInterface $entityManager): Response
    {
        $aviss = $entityManager
            ->getRepository(Avis::class)
            ->findAll();



        return $this->render('avis/back.html.twig', [
            'aviss' => $aviss
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
