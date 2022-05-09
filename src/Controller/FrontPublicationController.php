<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Entity\Region;
use App\Entity\User;
use App\Form\CommentaireType;
use App\Form\PublicationType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use mofodojodino\ProfanityFilter\Check;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Twilio\Rest\Client;

class FrontPublicationController extends AbstractController
{
    /**
     * @Route("/frontpublication", name="app_front_publication")
     */
    public function index(EntityManagerInterface $entityManager,Request $request, PaginatorInterface $paginator): Response
    {
        $publications = $paginator->paginate(
        $publications = $entityManager
            ->getRepository(Publication::class)
            ->findAll(),
        $request->query->getInt('page', 1),
        3
        );


        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $ci = $this->getUser();
            $userRole = $this->getUser()->getRoles();
            $cin=$this->getUser();

        }else {
            $userCon = 0;
            $userName = "";
            $ci = null;
            $cin =null;
            $userRole = null;
        }

        return $this->render('publication/FrontPublication.html.twig', [
            'publications' => $publications,'userCon' => $userCon,
            'userName' => $userName,
            'Usercin' =>$ci,
            'userRole' =>$userRole,
            'user'=>$cin,
        ]);
    }
    /**
     * @Route("/newpublication", name="app_publicationfront_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['image']->getData();
            $lastFile = $publication->getImage();
            if ($form['image']->getData() == null){

                $publication->setImage($lastFile);
            }
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/image';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessClientExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $publication->setImage($newFilename);
            }
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('app_front_publication');
        }

        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $ci = $this->getUser();
            $userRole = $this->getUser()->getRoles();
            $cin=$this->getUser();

        }else {
            $userCon = 0;
            $userName = "";
            $ci = null;
            $cin = null;
            $userRole = null;
        }

        return $this->render('publication/new.html.twig', [
            'publication' => $publication,
            'form' => $form->createView(),'userCon' => $userCon,
            'userName' => $userName,
            'Usercin' =>$ci,
            'userRole' =>$userRole,
            'user'=>$cin,
        ]);
    }





    /**
     * @Route("/f/{id}", name="app_publication_front_show")
     */
    public function show(Publication $publication, EntityManagerInterface $entityManager,$id, Request $request): Response
    { $em=$this->getDoctrine()->getManager();
        $commentaires = $entityManager
        ->getRepository(Commentaire::class)
        ->findBy(['idPublication'=>$id,'etat'=>"Afficher"]);
        $query=$entityManager
        ->createQuery("select count(s) from App\Entity\Commentaire s where s.idPublication=:id AND s.etat='Afficher'")
            ->setParameter('id',$id);
        $number=$query->getSingleScalarResult();
        $dql = "SELECT AVG(e.note) AS rating FROM App\Entity\Commentaire e "."WHERE e.idPublication = :id AND e.etat='Afficher' ";
$rating = $em->createQuery($dql)
    ->setParameter('id', $id)
    ->getSingleScalarResult();


        $check = new Check( '../config/profanities.php');

        $commentaires2 = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $commentaires2);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $sid    = "AC42a59cc79a1a7df5e584c05fd0350f55";
            $token  = "207f9bd0ea1e54c7e6a5cb157af8122e";
            $twilio = new Client($sid, $token);

            $message = $twilio->messages
                ->create("+21694337950", // to
                    array(
                        "messagingServiceSid" => "MGdc6f5b3b0e0674e1fb181165ffa1db35",
                        "body" => "Cette publication a atteint un nombre inquiéttant de zeros , priére de verifier cette publication."
                    )
                );

            print($message->sid);

            $verifier = $form['comment']->getData();
            $hasProfanity = $check->hasProfanity($verifier);
            if ($hasProfanity == false) {
                $commentaires2->setIdPublication($publication);
                $entityManager->persist($commentaires2);
                $entityManager->flush();
                $good="good";



                return $this->redirectToRoute('app_publication_front_show',[
                    "id"=>$id,"good"=>$good
                ]);
            }else {
$bad="bad";


                return $this->redirectToRoute('app_publication_front_show',[
                    "id"=>$id,"bad"=>$bad
                ]);
            }
        }

        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $ci = $this->getUser();
            $userRole = $this->getUser()->getRoles();
            $cin=$this->getUser();

        }else {
            $userCon = 0;
            $userName = "";
            $ci = null;
            $cin = null;
            $userRole = null;
        }
        return $this->render('publication/show_front.html.twig', [
            'publication' => $publication,'commentaires'=>$commentaires,'commentairesform'=>$commentaires2,
            'form' => $form->createView(),'idpub'=>$id,'number'=>$number,'rating'=>$rating,'userCon' => $userCon,
            'userName' => $userName,
            'Usercin' =>$ci,
            'userRole' =>$userRole,
            'user'=>$cin,
        ]);
    }
    /**
     * @Route("/{id}/edit", name="app_publication_edit")
     */
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['image']->getData();
            $lastFile = $publication->getImage();
            if ($form['image']->getData() == null){

                $publication->setImage($lastFile);
            }
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/image';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessClientExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $publication->setImage($newFilename);
            }
            $entityManager->persist($publication);
            $entityManager->flush();


            return $this->redirectToRoute('app_front_publication');
        }

        if ($this->getUser() ){
            $userCon = $this->getUser()->getCin();
            $userName = $this->getUser()->getNom();
            $ci = $this->getUser();
            $userRole = $this->getUser()->getRoles();
            $cin=$this->getUser();

        }else {
            $userCon = 0;
            $userName = "";
            $ci = null;
            $cin = null;
            $userRole = null;
        }
        return $this->render('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form->createView(),'userCon' => $userCon,
            'userName' => $userName,
            'Usercin' =>$ci,
            'userRole' =>$userRole,
            'user'=>$cin,
        ]);
    }
    /**
     * @Route("/Supprimerf/{id}", name="app_publication_front_delete")
     */
    public function delete(Publication $publication, EntityManagerInterface $entityManager)
    {

        $entityManager->remove($publication);
        $entityManager->flush();


        return $this->redirectToRoute('app_front_publication');
    }
    /**
     * @Route("/Supprimerfrontcom/{id}_{idpub}", name="app_commentaire_front_delete")
     */
    public function deleteComment(Commentaire $commentaire, EntityManagerInterface $entityManager,$idpub)
    {

        $entityManager->remove($commentaire);
        $entityManager->flush();


        return $this->redirectToRoute('app_publication_front_show',array('id'=>$idpub));
    }
    /**
     * @Route("/frontpublicationMobile", name="app_front_publicationMobile")
     */
    public function indexMobile(EntityManagerInterface $entityManager,NormalizerInterface $normalizer): Response
    {

            $publications = $entityManager
                ->getRepository(Publication::class)
                ->findAll();
            $Publication = $normalizer ->normalize($publications,'json');

        return new Response(json_encode($Publication));
    }
    /**
     * @Route("/frontpublicationMobileadd", name="app_front_publicationMobileadd")
     */
    public function indexMobileadd(Request $request,NormalizerInterface $normalizer):Response
    {
        $region=$this->getDoctrine()->getRepository(Region::class)->findOneBy(['id'=>10]);
        $guest=$this->getDoctrine()->getRepository(User::class)->findOneBy(['cin'=>1234568]);
        $em=$this->getDoctrine()->getManager();
        $Publication=new Publication();
        $Publication->setNom($request->get('nom'));
        $Publication->setDescription($request->get('description'));
        $Publication->setImage($request->get('image'));
        $Publication->setAdresse($request->get('adresse'));

        $Publication->setRegion($region);

        $Publication->setIdGuest($guest);
$em->persist($Publication);
$em->flush();

        $Publication = $normalizer ->normalize($Publication,'json');

        return new Response(json_encode($Publication));
    }




}
