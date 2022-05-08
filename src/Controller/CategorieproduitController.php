<?php

namespace App\Controller;

use App\Entity\Categorieproduit;
use App\Form\CategorieproduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorieproduit")
 */
class CategorieproduitController extends AbstractController
{
    /**
     * @Route("/", name="app_categorieproduit_index", methods={"GET", "POST"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $categorieproduitA = new Categorieproduit();
        $form = $this->createForm(CategorieproduitType::class, $categorieproduitA);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorieproduitA);
            $entityManager->flush();
            $entityManager->clear();


            return $this->redirectToRoute('app_categorieproduit_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->getUser() ){

            $userRole = $this->getUser()->getRoles();
            $ci = $this->getUser();
        }else{

            $ci = null;
            $userRole = null;
        }

        $categorieproduits = $entityManager
            ->getRepository(Categorieproduit::class)
            ->findAll();

        return $this->render('categorieproduit/index.html.twig', [
            'categorieproduits' => $categorieproduits,
            'form' => $form->createView(),
            'user'=>$cin,
            'Usercin' =>$ci,
            'userRole' =>$userRole
        ]);
    }

    /**
     * @Route("/new", name="app_categorieproduit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieproduit = new Categorieproduit();
        $form = $this->createForm(CategorieproduitType::class, $categorieproduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorieproduit);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorieproduit_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->getUser() ){

            $userRole = $this->getUser()->getRoles();
            $ci = $this->getUser();
        }else{

            $ci = null;
            $userRole = null;
        }

        return $this->render('categorieproduit/new.html.twig', [
            'categorieproduit' => $categorieproduit,
            'form' => $form->createView(),
            'Usercin' =>$ci,
            'userRole' =>$userRole
        ]);
    }

    /**
     * @Route("/{nomcategorie}", name="app_categorieproduit_show", methods={"GET"})
     */
    public function show(Categorieproduit $categorieproduit): Response
    {
        return $this->render('categorieproduit/show.html.twig', [
            'categorieproduit' => $categorieproduit,
        ]);
    }

    /**
     * @Route("/{nomcategorie}/edit", name="app_categorieproduit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categorieproduit $categorieproduit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieproduitType::class, $categorieproduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorieproduit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorieproduit/edit.html.twig', [
            'categorieproduit' => $categorieproduit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{nomcategorie}", name="app_categorieproduit_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorieproduit $categorieproduit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieproduit->getNomcategorie(), $request->request->get('_token'))) {
            $entityManager->remove($categorieproduit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorieproduit_index', [], Response::HTTP_SEE_OTHER);
    }
}