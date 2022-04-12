<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
Use Gedmo\Sluggable\Util\Urlizer;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="MesReclamations")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $recs = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();
        return $this->render('reclamation/index.html.twig', [
            'recs' => $recs,
        ]);
    }
    /**
     * @Route("/back/reclamation", name="Reclamations")
     */
    public function Reclamations(EntityManagerInterface $entityManager): Response
    {
        $recls = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();
        return $this->render('reclamation/back.html.twig', [
            'recls' => $recls,
        ]);
    }
    /**
     * @Route("/newReclamation", name="app_reclamationfront_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
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
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('MesReclamations');
        }

        return $this->render('reclamation/Ajout.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="app_reclamation_detail")
     */
    public function show(Reclamation $reclamation,EntityManagerInterface $entityManager): Response
    {
        if ($reclamation->getStatut()=="EnAttente"){
        $reclamation->setDatetraitement(new \DateTime());
        $reclamation->setStatut("EnCours");}
        if ($reclamation->getStatut()=="EnCours"){
            $reclamation->setStatut("Traitée");
        }
        $entityManager->persist($reclamation);
        $entityManager->flush();
        return $this->render('reclamation/detail.html.twig', [
            'reclamation' => $reclamation,
        ]);

    }
    /**
     * @Route("/papier/{id}", name="app_reclamation_papier")
     */
    public function print(Reclamation $reclamation): Response
    {

        return $this->render('reclamation/print.html.twig', [
            'reclamation' => $reclamation,
        ]);

    }
    /**
     * @Route("/Supprimer/{id}", name="app_reclamation_delete")
     */
    public function delete(Reclamation $reclamation, EntityManagerInterface $entityManager)
    {

        $entityManager->remove($reclamation);
        $entityManager->flush();


        return $this->redirectToRoute('Reclamations');
    }

}
