<?php

namespace App\Controller;

use App\Entity\Categorieproduit;
use App\Entity\Panierdetails;
use App\Entity\Produit;
use App\Form\PanierdetailsType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/panierdetails")
 */
class PanierdetailsController extends AbstractController
{

    /**
     * @Route ("/add/{idProd}", name="ajout_panier")
     */
    public function add($idProd, SessionInterface $session)
    {

        $panier = $session->get('panier', []);

        if (!empty($panier[$idProd])) {
            $panier[$idProd]++;
        } else {
            $panier[$idProd] = 1;
        }

        $session->set('panier', $panier);

        return $this-> redirectToRoute("app_panierdetails_index");
        /*dd($panier);*/

    }
    /**
     * @Route ("/dec/{idProd}", name="dec_panier")
     */
    public function decrease($idProd, SessionInterface $session)
    {

        $panier = $session->get('panier', []);

        if (!empty($panier[$idProd])) {
            $panier[$idProd]--;
        } else {
            $panier[$idProd] = 0;
        }

        $session->set('panier', $panier);
        return $this-> redirectToRoute("app_panierdetails_index");

        /*dd($panier);*/

    }

    /**
     * @Route ("/remove/{idProd}", name="remove_panier")
     */
    public function remove($idProd, SessionInterface $session){
        $panier= $session->get('panier', []);

            unset($panier[$idProd]);
        $session->set('panier', $panier);

        return $this-> redirectToRoute("app_panierdetails_index");
    }

    /**
     * @Route("/", name="app_panierdetails_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager, SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $panierdetails = $entityManager
            ->getRepository(Panierdetails::class)
            ->findAll();
        $produits = $entityManager
            ->getRepository(Panierdetails::class)
            ->findAll();

        $panier = $session->get('panier', []);


        $panierwithdata = [];

        foreach ($panier as $id => $quantity) {

            $panierwithdata[] = [
                'produit' => $produitRepository->find($id),
                'quantity' => $quantity,
            ];
        }
        $total = 0;
        foreach ($panierwithdata as $item) {
            $totalItem = $item['produit']->getPrix() * $item['quantity'];
            $total += $totalItem;
        }
        return $this->render('panierdetails/PanierIndex.html.twig', [
            'items' => $panierwithdata,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/index", name="app_panierdetails_index2", methods={"GET"})
     */
    public function index2(EntityManagerInterface $entityManager): Response
    {
        $panierdetails = $entityManager
            ->getRepository(Panierdetails::class)
            ->findAll();
        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();
        $categories = $entityManager
            ->getRepository(Categorieproduit::class)
            ->findAll();

        return $this->render('panierdetails/test.html.twig', [
            'panierdetails' => $panierdetails,
            'produits' => $produits,
            'categories' => $categories
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
        if ($this->isCsrfTokenValid('delete' . $panierdetail->getId(), $request->request->get('_token'))) {
            $entityManager->remove($panierdetail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panierdetails_index', [], Response::HTTP_SEE_OTHER);
    }
}
