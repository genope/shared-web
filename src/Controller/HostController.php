<?php

namespace App\Controller;
use Knp\Component\Pager\PaginatorInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Event;
use App\Form\EventType;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\EventRepository;
class HostController extends AbstractController
{
    /**
     * @Route("/listevent", name="listevent")
     */
    public function listevent(Request $request,PaginatorInterface $paginator): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $ci = $this->getUser();

        $repo = $this->getDoctrine()->getRepository(Event::class);
        $ev =$repo->findAll();

        $ev = $paginator->paginate(
            $ev, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
     //   return $this->render('host/listevent.html.twig', array("events" => $ev));
        return $this->render('host/listevent.html.twig', array("events" => $ev,'user' =>$cin,
            'Usercin' =>$ci));

    }


    /**
     * @Route("/listres", name="listres")
     */

    public function listresv(Request $request,PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $cin = $this->getUser()->getRoles();
        $ci = $this->getUser();
        $repo = $this->getDoctrine()->getRepository(Reservation::class);
        $r =$repo->findAll();
        $r = $paginator->paginate(
            $r, // Requête contenant les données à paginer
            $request->query->getInt('page', 1),
            3 // Nombre de résultats par page
        );
        return $this->render('host/listres.html.twig', array("res" => $r,'user' =>$cin,
            'Usercin' =>$ci));
    }


    /**
     * @Route("/addevent", name="addevent")
     */


    public function addevent(Request $request,\Swift_Mailer $mailer)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $ci = $this->getUser();
        $event = new Event(); //objet create instance
        $form = $this->createForm(EventType::class, $event);

        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $nomevent= $form->get('nomevent')->getData();
           $photo = $form->get('image')->getData();
            $fileName = bin2hex(random_bytes(6)).'.'.$photo->guessExtension();
            $photo->move ($this->getParameter('images_directory'),$fileName);
            $event->setImage($fileName);
            $em = $this->getDoctrine()->getManager(); //get manager bch tbadel fel base
                $em->persist($event); //$c baathtou feragh tawa b des donnees
                $em->flush();
            $message = (new \Swift_Message('Confirmation dajout devenement'))
                ->setFrom('tnsharedinc@gmail.com')
                ->setTo('testbentest152@gmail.com')
                ->setBody($this->renderView('Emails/confirmationEv.html.twig'),'text/html');
            $mailer ->send($message);



            return $this->redirectToRoute('listevent');
        }
            return $this->render("host/addevent.html.twig", array('form' => $form->createView(),'user' =>$cin,
                'Usercin' =>$ci)); //return the form

        }



    /**
     * @Route("/supp/{idevent}",name="delete")
     */
    public function delete($idevent): Response
    {

        $v = $this->getDoctrine()->getRepository(Event::class)->find($idevent);
        $em = $this->getDoctrine()->getManager();
        $em->remove($v);
        $em->flush();
        return $this->redirectToRoute("listevent");
    }

    /**
     * @Route("/update/{idevent}", name="update")
     */
    public function updateevent(Request $request,$idevent)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $ci = $this->getUser();
        $event = $this->getDoctrine()->getRepository(Event::class)->find($idevent);
        $form = $this->createForm(EventType::class, $event);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $file = $form->get('image')->getData();
            $fileName = bin2hex(random_bytes(6)).'.'.$file->guessExtension();
            $file->move ($this->getParameter('images_directory'),$fileName);
            $event->setImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listevent');
        }
        return $this->render("host/modifyevent.html.twig",array('form'=>$form->createView(),'user' =>$cin,
            'Usercin' =>$ci));
    }

    /**
     * @Route("/imprimEvent", name="imprimEvent", methods={"GET"})
     */
    public function imprim(): Response

    {

        $repo = $this->getDoctrine()->getRepository(Event::class);
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('Pdf/eventpdf.html.twig', [
            'events' => $repo->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Liste evenements.pdf", [
            "Attachment" => true
        ]);
    }
    /**
     * @Route("/statevent", name="statevent")
     */
    public function statevent()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $ci = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Event::class);
        $events = $repository->findAll();

        $t=0;
        $ar=0;
        $b=0;
        $other=0;


        foreach ($events as $events)
        {
        //    if (  $events->getNbparticip()>100)  :
            if (($events->getNbparticip()>100) and ($events->getNbparticip()<200)) :

                $t+=1;
          //  elseif (($events->getNbparticip()>100) and ($events->getNbparticip()<200)) :

            //    $ar+=1;
            elseif (($events->getNbparticip()>200) and ($events->getNbparticip()<300)) :

                $b+=1;
                elseif ($events->getNbparticip()>300)  :

                    $ar+=1;

            else :
                $other +=1;

            endif;

        }


        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['events', 'nombre'],
           //     ['nombre de participants>100',     $t],
                ['nombre de participants entre [100-200]',      $t],
                ['nombre de participants entre [200-300]',   $b],
                ['nombre de participants >300',   $ar]

            ]
        );
        $pieChart->getOptions()->setTitle('Top évenements ');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('red');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('statevent/statevent.html.twig', array('piechart' => $pieChart,'user' =>$cin,
            'Usercin' =>$ci));
    }

    /**
     * @Route("/trievent", name="trievent")
     */
    public function trievent()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cin = $this->getUser()->getRoles();
        $ci = $this->getUser();

     //   $evenement=$this->getDoctrine()->getRepository(Event::class)->find($idevent);

        $trievent=$this->getDoctrine()->getRepository(Event::class)->findEventByTri();

     //   return $this->redirectToRoute('listevent');
        return $this->render('host/trievent.html.twig', array("trievent" => $trievent,'user' =>$cin,
            'Usercin' =>$ci));

    }


}
