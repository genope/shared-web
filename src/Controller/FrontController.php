<?php

namespace App\Controller;
use App\Service\MailerService;
use App\Entity\Event;
use App\Entity\Reservation;
use App\Form\EventType;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class FrontController extends AbstractController
{
    /**
     * @Route("/events", name="events")
     */
    public function listevent(Request $request,PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->getUser()){
            $ci = $this->getUser();
        }else{
            $ci= null;
        }
        $repo = $this->getDoctrine()->getRepository(Event::class);
        $ev =$repo->findAll();
        $ev = $paginator->paginate(
            $ev, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
        return $this->render('front/events.html.twig', array("events" => $ev,'Usercin' =>$ci));
    }
    /**
         * @Route("/listreservation", name="listreservation")
     */

    public function listres(Request $request,PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->getUser()){
            $ci = $this->getUser();
        }else{
            $ci= null;
        }
        $repo = $this->getDoctrine()->getRepository(Reservation::class);
        $r =$repo->findAll();
        $r = $paginator->paginate(
            $r, // Requête contenant les données à paginer
            $request->query->getInt('page', 1),
            3 // Nombre de résultats par page
        );

        return $this->render('front/listreservation.html.twig', array("res" => $r,'Usercin' =>$ci,));
    }

    /**
     * @Route("/addres", name="addres")
     */


    public function addres(Request $request,\Swift_Mailer $mailer
    )
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->getUser()){
            $ci = $this->getUser();
        }else{
            $ci= null;
        }
        $r = new Reservation(); //objet create instance
        $form = $this->createForm(ReservationType::class, $r);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data=$form->getData();
            $r->setIdguest($ci);
            $em = $this->getDoctrine()->getManager();
            $em->persist($r);
            $em->flush();
            $message = (new \Swift_Message('Confirmation de réservation'))
                ->setFrom('tnsharedinc@gmail.com')
                ->setTo('testbentest152@gmail.com')
                ->setBody($this->renderView('Emails/confirmationRes.html.twig'),'text/html');
            $mailer ->send($message);
            return $this->redirectToRoute('listreservation');

        }
        return $this->render("front/addreservation.html.twig", array('form' => $form->createView(),'Usercin' =>$ci)); //return the form

    }

    /**
     * @Route("/updater/{idreserv}", name="updater")
     */
    public function updater(Request $request,$idreserv)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->getUser()){
            $ci = $this->getUser();
        }else{
            $ci= null;
        }
        $event = $this->getDoctrine()->getRepository(Reservation::class)->find($idreserv);
        $form = $this->createForm(ReservationType::class, $event);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listreservation');
        }
        return $this->render("front/modifyres.html.twig",array('form'=>$form->createView(),'Usercin' =>$ci));
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
