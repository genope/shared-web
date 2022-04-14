<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Offres;
use App\Entity\User;
use App\Form\OffresType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/offres")
 */
class OffresController extends AbstractController
{
    /**
     * @Route("/", name="app_offres_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $offres = $entityManager
            ->getRepository(Offres::class)
            ->findAll();

        return $this->render('offres/GridOffres.html.twig', [
            'offres' => $offres,
        ]);
    }
    /**
     * @Route("/mine", name="mine", methods={"GET"})
     */
    public function MesOffre(EntityManagerInterface $entityManager): Response
    {
        $offres = $entityManager
            ->getRepository(Offres::class)
            ->findAll();

        return $this->render('offres/MesOffres.html.twig', [
            'offres' => $offres,
        ]);
    }

    /**
     * @Route("/list", name="liste_offre", methods={"GET"})
     */
    public function listeindex(EntityManagerInterface $entityManager): Response
    {
        $offres = $entityManager
            ->getRepository(Offres::class)
            ->findAll();

        return $this->render('offres/ListesOffres.html.twig', [
            'offres' => $offres,
        ]);
    }


    /**
     * @Route("/new", name="app_offres_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offre = new Offres();
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



            $offre = new Offres();
            $form = $this->createForm(OffresType::class, $offre);
        }

        return $this->render('offres/new.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
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
