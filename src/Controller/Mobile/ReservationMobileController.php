<?php
namespace App\Controller\Mobile;

use App\Entity\Reservation;
use App\Repository\EventRepository;
use App\Repository\OffresRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepo;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/reservation")
 */
class ReservationMobileController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findAll();

        if ($reservations) {
            return new JsonResponse($reservations, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, UserRepo $userRepository, EventRepository $eventRepository, OffresRepository $offreRepository): JsonResponse
    {
        $reservation = new Reservation();

        return $this->manage($reservation, $userRepository,  $eventRepository,  $offreRepository,  $request, false);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, ReservationRepository $reservationRepository, UserRepo $userRepository, EventRepository $eventRepository, OffresRepository $offreRepository): Response
    {
        $reservation = $reservationRepository->find((int)$request->get("id"));

        if (!$reservation) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($reservation, $userRepository, $eventRepository, $offreRepository, $request, true);
    }

    public function manage($reservation, $userRepository, $eventRepository, $offreRepository, $request, $isEdit): JsonResponse
    {   
        $guest = $userRepository->find((int)$request->get("guest"));
        if (!$guest) {
            return new JsonResponse("guest with id " . (int)$request->get("guest") . " does not exist", 203);
        }
        
        $event = $eventRepository->find((int)$request->get("event"));
        if (!$event) {
            return new JsonResponse("event with id " . (int)$request->get("event") . " does not exist", 203);
        }
        
        $offre = $offreRepository->find((int)$request->get("offre"));
        if (!$offre) {
            return new JsonResponse("offre with id " . (int)$request->get("offre") . " does not exist", 203);
        }
        
        
        $reservation->setUp(
            $guest,
            $event,
            $offre,
            DateTime::createFromFormat("d-m-Y", $request->get("datedebut")),
            DateTime::createFromFormat("d-m-Y", $request->get("datefin"))
        );

        if (!$isEdit) {
            $email = $reservation->getIdguest()->getEmail();

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                try {
                    $transport = new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
                    $transport->setUsername('pidev.app.esprit@gmail.com')->setPassword('pidev-cred');
                    $mailer = new Swift_Mailer($transport);
                    $message = new Swift_Message('Welcome');
                    $message->setFrom(array('pidev.app.esprit@gmail.com' => 'Bienvenu'))
                        ->setTo(array($email))
                        ->setBody("<h1>Reservation ajouté avec succes</h1>", 'text/html');
                    $mailer->send($message);
                } catch (Exception $exception) {
                    return new JsonResponse(null, 405);
                }
            }
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reservation);
        $entityManager->flush();

        return new JsonResponse($reservation, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository): JsonResponse
    {
        $reservation = $reservationRepository->find((int)$request->get("id"));

        if (!$reservation) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($reservation);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findAll();

        foreach ($reservations as $reservation) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }
    
}
