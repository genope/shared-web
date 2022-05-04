<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Offres;
use App\Entity\User;
use App\Form\OffresType;
use App\Repository\OffresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

use MercurySeries\FlashyBundle\FlashyNotifier;

/**
 * @Route("/offres")
 */
class OffresController extends AbstractController
{

    /**
     * @Route("/send/{id}", name="send", methods={"GET", "POST"})
     */

    public function Approu(Request $request,Offres $offre,  OffresRepository $repo,\Swift_Mailer $mailer): Response
    {


        $message = (new \Swift_Message('Confirmation'))

        ->setFrom('yeektheb@gmail.com')
        ->setTo($offre->getIdUser()->getEmail())
        ->setBody($this->renderView('offres/test.html.twig',
        ['offre' => $offre,
         'user'=>$offre->getIdUser()
            ])
            ,'text/html');
        $mailer ->send($message);

        $nbr = $repo->Approuver($offre->getIdOffre());

        return $this->redirectToRoute('Approuver');

      
    }
    /**
     * @Route("/", name="app_offres_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $offres = $entityManager
            ->getRepository(Offres::class)
            ->findAll();
        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $ci = $this->getUser();
            $userRole = $this->getUser()->getRoles();

        }else {
            $userCon = 0;
            $userName = "";
            $ci = null;
            $userRole = null;
        }
        return $this->render('offres/GridOffres.html.twig', [
            'offres' => $offres,
                'userCon' => $userCon,
                'userName' => $userName,
            'Usercin' =>$ci,
            'userRole' =>$userRole
        ]);
    }
    /**
     * @Route("/mine", name="mine", methods={"GET"})
     */
    public function MesOffre(EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $Usercin = $this->getUser();

        $offres = $entityManager
            ->getRepository(Offres::class)
            ->findBy([
                'idUser' => $Usercin,
            ]);

        return $this->render('offres/MesOffres.html.twig', [
            'offres' => $offres,
            'user'=>$cin,
            'Usercin'=>$Usercin,
        ]);
    }
        /**
     * @Route("/dashboard", name="app_dashboard", methods={"GET"})
     */
    public function MesStatistique(OffresRepository $repo): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser();
        $user = $this->getUser()->getRoles();



        $Maison = $repo->findBy([
            'idUser' => $cin,
            'categ' => "Maison",
        ]);
        $Appartement = $repo->findBy([
            'idUser' => $cin,
            'categ' => "Appartement",
        ]);
        $Chambre = $repo->findBy([
            'idUser' => $cin,
            'categ' => "Chambre",
        ]);
        $Voiture = $repo->findBy([
            'idUser' => $cin,
            'categ' => "Voiture",
        ]);
        $Vélo = $repo->findBy([
            'idUser' => $cin,
            'categ' => "Vélo",
        ]);
        $Moto = $repo->findBy([
            'idUser' => $cin,
            'categ' => "Moto",
        ]);

                return $this->render('offres/Dashboard.html.twig', [
                'Maison' => count($Maison),
                'Appartement' => count($Appartement),
                'Chambre' => count($Chambre),
                'Voiture' => count($Voiture),
                'Vélo' => count($Vélo),
                'Moto' => count($Moto),
                    'user' => $user,
                    'Usercin' =>$cin,

                ]);
    }

     /**
     * @Route("/Approuver", name="Approuver", methods={"GET", "POST"})
     */
    public function Approuver(EntityManagerInterface $entityManager): Response
    {

        
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $ci = $this->getUser();

        $offres = $entityManager
            ->getRepository(Offres::class)
            ->findAll([
                'etat' => "0",
            ]);

       

                return $this->render('offres/ApprouverOffres.html.twig', [
                    'offres' => $offres,
                    'user' =>$cin,
                    'Usercin' =>$ci,

                ]);
    }

    /**
     * @Route("/list", name="liste_offre", methods={"GET"})
     */
    public function listeindex(Request $request,EntityManagerInterface $entityManager,PaginatorInterface $paginator): Response
    {
        $offres = $entityManager
            ->getRepository(Offres::class)
            ->findAll();
        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $ci = $this->getUser();
        }else {
            $userCon = 0;
            $userName = "";
        }

        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $ci = $this->getUser();
            $userRole = $this->getUser()->getRoles();

        }else {
            $userCon = 0;
            $userName = "";
            $ci = null;
            $userRole = null;

        }


       $liste_Offres = $paginator->paginate($offres,$request->query->getInt('page',1),3);
        return $this->render('offres/ListesOffres.html.twig', [
            'offres' => $offres,
            'filtre'=>$liste_Offres,
            'userCon' => $userCon,
            'userName' => $userName,
            'Usercin' =>$ci,
            'userRole' =>$userRole

        ]);
    }


    /**
     * @Route("/new", name="app_offres_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,FlashyNotifier $flashy): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();

        $offre = new Offres();


        $form = $this->createForm(OffresType::class, $offre);
        $form->handleRequest($request);


        if ($this->getUser() ){

            $userRole = $this->getUser()->getRoles();
            $ci = $this->getUser();
        }else{

            $ci = null;
            $userRole = null;
        }

        if ($form->isSubmitted() && $form->isValid()) {


            $file = $form->get('image')->getData();
            $newFilename = md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('upload_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
            }


            $offre->setIdUser($ci);
            $offre->setEtat(false);
             if($offre->getCateg() == 'Appartement' || $offre->getCateg() == 'Maison' || $offre->getCateg() == 'Chambre'){
                 $offre->setType("Logement");
             }
            elseif($offre->getCateg() == 'Voiture' || $offre->getCateg() == 'Moto' || $offre->getCateg() == 'Velo'){
                $offre->setType("MoyenDeTransport");
            }
            else{
                $offre->setType("Horeca");
            }
            $offre->setImage($newFilename);

            

            $entityManager->persist($offre);
            $entityManager->flush();



           
          $flashy->success('Event created!', 'http://your-awesome-link.com');


         
        }

        return $this->render('offres/new.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
            'Usercin' =>$ci,
            'userRole' =>$userRole,
            'user'=>$userRole,

        ]);
    }

    /**
     * @Route("/{idOffre}", name="app_offres_show", methods={"GET"})
     */

    public function show(Offres $offre): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(['cin' => $offre->getIdUser()]);


        return $this->render('offres/show.html.twig', [
            'offre' => $offre,
            'user'=>$offre->getIdUser(),
        ]);
    }
    /**
     * @Route("/{idOffre}/edit", name="app_offres_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Offres $offre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffresType::class, $offre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $file = $form->get('image')->getData();
            $newFilename = md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('upload_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
            }
            $offre->setImage($newFilename);


            $entityManager->flush();


            return $this->redirectToRoute('mine', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('offres/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idOffre}", name="app_offres_delete", methods={"POST"})
     */
    public function delete(Request $request, Offres $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getIdOffre(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mine', [], Response::HTTP_SEE_OTHER);
    }


 
}
