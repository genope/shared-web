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
     * @Route("/commande", name="commande")
     */
    public function commande(Request $request, \Swift_Mailer $mailer, SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $panier = $session->get('panier', []);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $email = $this->getUser()->getEmail();


        $panierwithdata = [];

        foreach ($panier as $id  => $quantity) {

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

        $message = (new \Swift_Message('Confirmation'))
            ->setFrom('yeektheb@gmail.com')
            ->setTo($email)
            ->setBody($this->renderView('panierdetails/Commande.html.twig',[
                'items' => $panierwithdata,
                'total' => $total,
            ]),'text/html');

        $mailer ->send($message);
        $this->addFlash('message', 'Le message a ete envoyé');

        return $this->redirectToRoute('app_panierdetails_index');

    }

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

        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $userRole = $this->getUser()->getRoles();
            $ci = $this->getUser();
        }else{
            $userCon = 0;
            $userName = "";
            $ci = null;
            $userRole = ["USER_USER",null];
        }
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
            'userCon' => $userCon,
            'userName' => $userName,
            'Usercin' =>$ci,
            'userRole' =>$userRole
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
