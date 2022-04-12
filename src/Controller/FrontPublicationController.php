<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Form\CommentaireType;
use App\Form\PublicationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Gedmo\Sluggable\Util\Urlizer;

class FrontPublicationController extends AbstractController
{
    /**
     * @Route("/frontpublication", name="app_front_publication")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $publications = $entityManager
            ->getRepository(Publication::class)
            ->findAll();

        return $this->render('publication/FrontPublication.html.twig', [
            'publications' => $publications,
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

        return $this->render('publication/new.html.twig', [
            'publication' => $publication,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/f/{id}", name="app_publication_front_show")
     */
    public function show(Publication $publication, EntityManagerInterface $entityManager,$id, Request $request): Response
    { $commentaires = $entityManager
        ->getRepository(Commentaire::class)
        ->findBy(['idPublication'=>$id]);
        $query=$entityManager
        ->createQuery("select count(s) from App\Entity\Commentaire s where s.idPublication=:id  ")
            ->setParameter('id',$id);
        $number=$query->getSingleScalarResult();


        $commentaires2 = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaires2);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

$commentaires2->setIdPublication($publication);
            $entityManager->persist($commentaires2);
            $entityManager->flush();

            return $this->redirectToRoute('app_front_publication');
        }
        return $this->render('publication/show_front.html.twig', [
            'publication' => $publication,'commentaires'=>$commentaires,'commentairesform'=>$commentaires2,
            'form' => $form->createView(),'idpub'=>$id,'number'=>$number,
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

        return $this->render('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form->createView(),
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


}
