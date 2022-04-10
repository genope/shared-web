<?php

namespace App\Controller;

use App\Entity\Panierdetails;
use App\Form\PanierdetailsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/panierdetails")
 */
class PanierdetailsController extends AbstractController
{
    /**
     * @Route("/", name="app_panierdetails_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $panierdetails = $entityManager
            ->getRepository(Panierdetails::class)
            ->findAll();

        return $this->render('panierdetails/index.html.twig', [
            'panierdetails' => $panierdetails,
        ]);
    }

    /**
     * @Route("/new", name="app_panierdetails_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panierdetail = new Panierdetails();
        $form = $this->createForm(PanierdetailsType::class, $panierdetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panierdetail);
            $entityManager->flush();

            return $this->redirectToRoute('app_panierdetails_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panierdetails/new.html.twig', [
            'panierdetail' => $panierdetail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_panierdetails_show", methods={"GET"})
     */
    public function show(Panierdetails $panierdetail): Response
    {
        return $this->render('panierdetails/show.html.twig', [
            'panierdetail' => $panierdetail,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_panierdetails_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Panierdetails $panierdetail, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PanierdetailsType::class, $panierdetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panierdetails_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panierdetails/edit.html.twig', [
            'panierdetail' => $panierdetail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_panierdetails_delete", methods={"POST"})
     */
    public function delete(Request $request, Panierdetails $panierdetail, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panierdetail->getId(), $request->request->get('_token'))) {
            $entityManager->remove($panierdetail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panierdetails_index', [], Response::HTTP_SEE_OTHER);
    }
}
