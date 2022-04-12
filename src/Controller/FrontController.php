<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Reservation;
use App\Form\EventType;
use App\Form\ReservationType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/front", name="app_front")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    /**
     * @Route("/events", name="events")
     */
    public function listevent(): Response
    {

        $repo = $this->getDoctrine()->getRepository(Event::class);
        $ev =$repo->findAll();
        return $this->render('front/events.html.twig', array("events" => $ev));
    }
    /**
     * @Route("/listreservation", name="listreservation")
     */

    public function listres(): Response
    {

        $repo = $this->getDoctrine()->getRepository(Reservation::class);
        $r =$repo->findAll();
        return $this->render('front/listreservation.html.twig', array("res" => $r));
    }





    /**
     * @Route("/addres", name="addres")
     */


    public function addres(Request $request)
    {
        $r = new Reservation(); //objet create instance
        $form = $this->createForm(ReservationType::class, $r);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            #  $file = $event->getImage();
            #   $file = $form->get('photo')->getData();

            #$fileName = md5(uniqid()) . '.' . $file->guessExtension();





            $em = $this->getDoctrine()->getManager(); //get manager bch tbadel fel base
            $em->persist($r); //$c baathtou feragh tawa b des donnees
            $em->flush();
            return $this->redirectToRoute('listreservation');
        }
        return $this->render("front/addreservation.html.twig", array('form' => $form->createView())); //return the form

    }

    /**
     * @Route("/updater/{idreserv}", name="updater")
     */
    public function updater(Request $request,$idreserv)
    {
        $event = $this->getDoctrine()->getRepository(Reservation::class)->find($idreserv);
        $form = $this->createForm(ReservationType::class, $event);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {


            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listreservation');
        }
        return $this->render("front/modifyres.html.twig",array('form'=>$form->createView()));
    }
    /**
     * @Route("/deleter/{idreserv}",name="deleter")
     */
    public function delete($idreserv): Response
    {
        $v = $this->getDoctrine()->getRepository(Reservation::class)->find($idreserv);
        $em = $this->getDoctrine()->getManager();
        $em->remove($v);
        $em->flush();
        return $this->redirectToRoute("listreservation");
    }










}
