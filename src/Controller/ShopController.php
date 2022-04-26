<?php

namespace App\Controller;

use App\Entity\Categorieproduit;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    /**
     * @Route("/shop", name="app_shop")
     */
    public function indexGrid(EntityManagerInterface $entityManager): Response
    {
        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();
        $categories = $entityManager
            ->getRepository(Categorieproduit::class)
            ->findAll();
        if ($this->getUser()) {
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $userRole = $this->getUser()->getRoles();
            $ci = $this->getUser();
        } else {
            $userCon = 0;
            $userName = "";
            $ci = null;
            $userRole = null;
        }
        return $this->render('shop/indexFrontGrid.html.twig', [
            'controller_name' => 'ShopController',
            'produits' => $produits,
            'categories' => $categories,
            'userCon' => $userCon,
            'userName' => $userName,
            'Usercin' => $ci,
            'userRole' => $userRole
        ]);
    }

    /**
     * @Route("/shopList", name="app_shopList")
     */
    public function indexList(EntityManagerInterface $entityManager): Response
    {
        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();
        $categories = $entityManager
            ->getRepository(Categorieproduit::class)
            ->findAll();
        if ($this->getUser()) {
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $ci = $this->getUser();
            $userRole = $this->getUser()->getRoles();
        } else {

            $userCon = 0;
            $userName = "";
            $ci = null;
            $userRole = null;


            return $this->render('shop/indexFrontList.html.twig', [
                'controller_name' => 'ShopController',
                'produits' => $produits,
                'categories' => $categories,
                'userCon' => $userCon,
                'userName' => $userName,
                'Usercin' => $ci,
                'userRole' => $userRole,
            ]);
        }
    }


}

