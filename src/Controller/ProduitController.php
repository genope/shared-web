<?php

namespace App\Controller;

use App\Entity\Categorieproduit;
use App\Entity\Offres;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/ProduitMobile", name="ProduitMobile", methods={"GET"})
     */
    public function AfficherProduit(EntityManagerInterface $entityManager,ProduitRepository $repo,NormalizerInterface $Normalizer)
    {

        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();
        $categories = $entityManager
            ->getRepository(Categorieproduit::class)
            ->findAll();

        $json = $Normalizer->normalize($produits, 'json', ['groups' => 'produits']);

        return new Response(json_encode($json));
    }
    /**
     * @Route("/ProduitMobile/{idProd}", name="app_produit_show", methods={"GET"})
     */
    public function showMobile(Produit $produit,NormalizerInterface $Normalizer): Response
    {
        $json = $Normalizer->normalize($produit, 'json', ['groups' => 'produits']);

        return new Response(json_encode($json));
    }
    /**
     * @Route("/deleteProduit/{id}", name="mobileDelete")
     */
    public function deleteProduit(Request $request, NormalizerInterface $Normalizer, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $offre = $this->getDoctrine()->getRepository(Produit::class)->find($id);
        $em->remove($offre);
        $em->flush();
        return $this->json(["response" => "Produit Supprimé"]);
    }
    /**
     * @Route("/newMob", name="ProduitMobile/mobile_new", methods={"GET", "POST"})
     *
     */
    public function newMobile(Request $request, EntityManagerInterface $entityManager, NormalizerInterface $Normalizer): Response
    {

        $produit = new Produit();
        //$form = $this->createForm(ProduitType::class, $produit);
        //$form->handleRequest($request);
        $categorieproduit = $entityManager
            ->getRepository(categorieproduit::class)
            ->findAll();
        //$produit->setImage($newFilename);

        $entityManager = $this->getDoctrine()->getManager();

        $produit->setRefProd($request->query->get('refProd'));
        $produit->setDesignation($request->query->get('designation'));
        $prixfloat = floatval($request->query->get('prix'));
        $produit->setPrix($prixfloat);
        $produit->setQteStock($request->query->get('Qte'));

        $entityManager->persist($produit);
        $entityManager->flush();

        $json = $Normalizer->normalize($produit, 'json', ['groups' => 'produits']);
        return new Response(json_encode($json));

    }

    /**
     * @Route("/", name="app_produit_index", methods={"GET"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $donnees = $entityManager
            ->getRepository(Produit::class)
            ->findAll();
        $categories = $entityManager
            ->getRepository(Categorieproduit::class)
            ->findAll();

        $produits = $paginator->paginate(
            $donnees,
            $request -> query->getInt('page',1),
            4
        );
        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $userRole = $this->getUser()->getRoles();
            $ci = $this->getUser();
        }else{
            $userCon = 0;
            $userName = "";
            $ci = null;
            $userRole = null;
        }


        return $this->render('produit/index.html.twig', [
            'categories' => $categories,
            'produits' => $produits,
            'user'=>$cin,
            'Usercin' =>$ci,

        ]);
        //json

    }

    /**
     * @Route("/new", name="app_produit_new", methods={"GET", "POST"})
     *
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        $categorieproduit = $entityManager
            ->getRepository(categorieproduit::class)
            ->findAll();

        if ($this->getUser() ){
            $cin = $this->getUser()->getRoles();
            $ci = $this->getUser();
        }else{

            $ci = null;

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

            $produit->setImage($newFilename);
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'categories' => $categorieproduit,
            'produit' => $produit,
            'form' => $form->createView(),
            'user'=>$cin,
            'Usercin' =>$ci,

        ]);
    }

    /**
     * @Route("/{idProd}", name="app_produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {


        if ($this->getUser() ){
            $userRole = $this->getUser()->getRoles();
            $userCon = $this->getUser()->getCin();
            $ci = $this->getUser();
            $cin = $this->getUser()->getRoles();
            $userName = $this->getUser()->getNom();
        }else{
            $userCon = 0;
            $ci = null;
            $userRole = ["ROLE_USER",null];
            $userName = "";
        }
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'user'=>$userRole,
            'Usercin' =>$ci,
            'userRole' =>$userRole,
            'userCon' => $userCon,
            'userName' => $userName,
        ]);
    }

    /**
     * @Route("/{idProd}/edit", name="app_produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
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
            /*$originalFileName= pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);*/
            $newFilename = md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('upload_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
            }
            $produit->setImage($newFilename);

            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
            'user'=>$ci,
            'Usercin' =>$ci,
            'userRole' =>$userRole
        ]);
    }

    /**
     * @Route("/{idProd}", name="app_produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getIdProd(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
