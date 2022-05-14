<?php
namespace App\Controller\Mobile;

use App\Entity\Event;
use App\Repository\EventRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/event")
 */
class EventMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        if ($events) {
            return new JsonResponse($events, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $event = new Event();

        return $this->manage($event, $request);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find((int)$request->get("id"));

        if (!$event) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($event, $request);
    }

    public function manage($event, $request): JsonResponse
    {   
        $file = $request->files->get("file");
        if ($file) {
            $imageFileName = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('images_directory'), $imageFileName);
            } catch (FileException $e) {
                dd($e);
            }
        } else {
            if ($request->get("image")) {
                $imageFileName = $request->get("image");
            } else {
                $imageFileName = "null";
            }
        }
        
        $event->setUp(
            $request->get("nom"),
            DateTime::createFromFormat("d-m-Y", $request->get("dateDebut")),
            DateTime::createFromFormat("d-m-Y", $request->get("dateFin")),
            $imageFileName,
            (int)$request->get("nbParticipants"),
            $request->get("description"),
            $request->get("lieu")
        );
        
        

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($event);
        $entityManager->flush();

        return new JsonResponse($event, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, EventRepository $eventRepository): JsonResponse
    {
        $event = $eventRepository->find((int)$request->get("id"));

        if (!$event) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($event);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        foreach ($events as $event) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }
    
    /**
     * @Route("/image/{image}", methods={"GET"})
     */
    public function getPicture(Request $request): BinaryFileResponse
    {
        return new BinaryFileResponse(
            $this->getParameter('images_directory') . "/" . $request->get("image")
        );
    }
    
}
