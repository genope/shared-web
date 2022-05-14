<?php
namespace App\Controller\Mobile;

use App\Repository\OffresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/offre")
 */
class OffreMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(OffresRepository $offreRepository): Response
    {
        $offres = $offreRepository->findAll();

        if ($offres) {
            return new JsonResponse($offres, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }
}
