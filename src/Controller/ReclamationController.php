<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
Use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;



class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="MesReclamations")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();

        $cinn = $this->getUser()->getCin();

        $Usercin = $this->getUser();
        $recs = $entityManager
            ->getRepository(Reclamation::class)
            ->findBy(['iduser'=>$cinn]);

        return $this->render('reclamation/index.html.twig', [
            'recs' => $recs,
            'user'=>$cin
        ]);
    }
    /**
     * @Route("/back/reclamation", name="Reclamations")
     */
    public function Reclamations(EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request )
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $Usercin = $this->getUser();

        $données = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();
        $nbr = $entityManager
            -> createQuery('Select count(r) FROM App\Entity\Reclamation r ')
            ->getSingleScalarResult();
        $nbrA = $entityManager
            -> createQuery('Select count(r) FROM App\Entity\Reclamation r WHERE r.statut like :st ')
            -> setParameter('st','EnAttente')
            ->getSingleScalarResult();
        $nbrE = $entityManager
            -> createQuery('Select count(r) FROM App\Entity\Reclamation r WHERE r.statut like :st ')
            -> setParameter('st','EnCours')
            ->getSingleScalarResult();
        $nbrT = $entityManager
            -> createQuery('Select count(r) FROM App\Entity\Reclamation r WHERE r.statut like :st ')
            -> setParameter('st','Traitée')
            ->getSingleScalarResult();
        $temps = $entityManager
            -> createQuery('Select avg(date_diff(r.datetraitement,r.datecreation)) FROM App\Entity\Reclamation r ')

            ->getSingleScalarResult();
        $temps=round($temps);




        $recls = $paginator->paginate($données,$request->query->getInt('page',1),2  );

        return $this->render('reclamation/back.html.twig', [
            'recls' => $recls,'nbr'=>$nbr,'nbrA'=>$nbrA,'nbrE'=>$nbrE,'nbrT'=>$nbrT,'temps'=>$temps,'user'=>$cin,
            'Usercin'=>$Usercin
        ]);
    }



    /**
     * @Route("/back/reclamationEnCours", name="ReclamationsEnCours")
     */
    public function ReclamationsEc(EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request )
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $Usercin = $this->getUser();
        $query = $entityManager
            -> createQuery('Select r FROM App\Entity\Reclamation r WHERE r.statut LIKE :statut')->setParameter('statut','EnCours');
        $données=$query->getResult();
        $recls = $paginator->paginate($données,$request->query->getInt('page',1),4);
        $nbrE = $entityManager
            -> createQuery('Select count(r) FROM App\Entity\Reclamation r WHERE r.statut like :st ')
            -> setParameter('st','EnCours')
            ->getSingleScalarResult();
        return $this->render('reclamation/back.html.twig', [
            'recls' => $recls,'nbrE'=>$nbrE,'user'=>$cin,
            'Usercin'=>$Usercin
        ]);
    }
    /**
     * @Route("/back/reclamationEnAttente", name="ReclamationsEnAttente")
     */
    public function ReclamationsEA(EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request )
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $Usercin = $this->getUser();
        $query = $entityManager
            -> createQuery('Select r FROM App\Entity\Reclamation r WHERE r.statut LIKE :statut')->setParameter('statut','EnAttente');
        $données=$query->getResult();
        $recls = $paginator->paginate($données,$request->query->getInt('page',1),4);
        return $this->render('reclamation/back.html.twig', [
            'recls' => $recls,'user'=>$cin,
            'Usercin'=>$Usercin
        ]);
    }
    /**
     * @Route("/back/reclamationTraitee", name="ReclamationsTraitées")
     */
    public function ReclamationsT(EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request )
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $Usercin = $this->getUser();
        $query = $entityManager
            -> createQuery('Select r FROM App\Entity\Reclamation r WHERE r.statut LIKE :statut')->setParameter('statut','Traitée');
        $données=$query->getResult();
        $recls = $paginator->paginate($données,$request->query->getInt('page',1),4);
        return $this->render('reclamation/back.html.twig', [
            'recls' => $recls,'user'=>$cin,
            'Usercin'=>$Usercin
        ]);
    }

    /**
     * @Route("/newReclamation", name="app_reclamationfront_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $Usercin = $this->getUser();
        $mail = $this->getUser()->getEmail();
        $nom = $this->getUser()->getNom();
        $prenom = $this->getUser()->getPrenom();

        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['image']->getData();
            $lastFile = $reclamation->getImage();
            if ($form['image']->getData() == null){

                $reclamation->setImage($lastFile);
            }
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/Front-office/images';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessClientExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $reclamation->setImage($newFilename);

            }
            $reclamation->setStatut("EnAttente");
            $reclamation->setIduser($this->getUser());
            $reclamation->setEmail($mail);
            $reclamation->setNom($nom);
            $reclamation->setPrenom($prenom);
            $entityManager->persist($reclamation);
            $entityManager->flush();


            return $this->redirectToRoute('MesReclamations');

        }


        return $this->render('reclamation/Ajout.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),'user'=>$cin,
            'Usercin'=>$Usercin


        ]);
    }

    /**
     * @Route("/rec/{id}", name="app_reclamation_detail")
     */
    public function show(MailerInterface $mailer,Reclamation $reclamation,EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $Usercin = $this->getUser();
        if ($reclamation->getStatut()=="EnAttente"){
        $reclamation->setDatetraitement(new \DateTime());
        $reclamation->setStatut("EnCours");}

        if ($reclamation->getStatut()=="EnCours"){
            $reclamation->setStatut("Traitée");
            $email = (new Email())
                ->from(Address::create('SHARED <ali.boughnim@esprit.tn>'))
                ->to($reclamation->getEmail())
                ->subject('Réclamation traitée')
                ->html('
                <center><h1>Bonjour '.$reclamation->getPrenom().' '.$reclamation->getNom().' ,</h1></center>
                <p>Votre réclamation a été traitée avec succès.</p>
                
                
                ');
            $mailer->send($email);
        }



        $entityManager->persist($reclamation);
        $entityManager->flush();
        $idU = $reclamation->getIduser();
        $idO = $reclamation->getType();
        $id = $reclamation->getId();
        $query = $entityManager
            -> createQuery('Select r FROM App\Entity\Reclamation r WHERE r.iduser = :idU or r.type = :idO and r.id != :id ')->setParameter('idU',$idU)->setParameter('id',$id)->setParameter('idO',$idO);
        $RidU=$query->getResult();

        return $this->render('reclamation/detail.html.twig', [
            'reclamation' => $reclamation,'RidU'=>$RidU,'user'=>$cin,
            'Usercin'=>$Usercin
        ]);

    }
    /**
     * @Route("/papier/{id}", name="app_reclamation_papier")
     */
    public function print(Reclamation $reclamation): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $Usercin = $this->getUser();

        return $this->render('reclamation/print.html.twig', [
            'reclamation' => $reclamation,'user'=>$cin,
            'Usercin'=>$Usercin
        ]);

    }
    /**
     * @Route("/S/{id}", name="app_reclamation_delete")
     */
    public function delete(Reclamation $reclamation, EntityManagerInterface $entityManager)
    {


        $em=$this->getDoctrine()->getManager();

        $entityManager->remove($reclamation);
        $entityManager->flush();



        return $this->redirectToRoute('Reclamations');
    }



}
