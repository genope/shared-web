<?php


namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reclamation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;

class RecControllerJson extends  AbstractController
{


    /******************Ajouter Reclamation*****************************************/
    /**
     * @Route("/addReclamation", name="add_reclamation")
     * @Method("POST")
     */

    public function ajouterReclamationAction(Request $request): JsonResponse
    {
        $Rec = new Reclamation();
        $em = $this->getDoctrine()->getManager();
        $Rec->setType($request->get('type'));
        $Rec->setObjet($request->get('objet'));
        $Rec->setDescription($request->get('description'));
        $Rec->setEmail($request->get('email'));
        $Rec->setNom($request->get('nom'));
        $Rec->setPrenom($request->get('prenom'));
        $Rec->setImage($request->get('image'));
        $Rec->setVocal(null);
        $Rec->setDatetraitement(null);
        $Date = new \DateTime('now');
        $Rec->setDatecreation($Date);
        $finduer = $this -> getDoctrine()->getRepository(User::class)->find(57575757);
        $Rec->setIduser($finduer);

        $Rec->setStatut('EnAttente');


        $em->persist($Rec);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Rec);
        return new JsonResponse($formatted);

    }

    /******************Supprimer Reclamation*****************************************/

    /**
     * @Route("/deleteReclamation", name="delete_reclamation")
     * @Method("DELETE")
     */

    public function deleteReclamationAction(Request $request): JsonResponse
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->find($id);
        if($reclamation!=null ) {
            $em->remove($reclamation);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Reclamation a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id reclamation invalide.");


    }

    /******************Modifier Reclamation*****************************************/
    /**
     * @Route("/updateReclamation", name="update_reclamation")
     * @Method("PUT")
     */
    public function modifierReclamationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getManager()
            ->getRepository(Reclamation::class)
            ->find($request->get("id"));

        $reclamation->setObjet($request->get("objet"));
        $reclamation->setDescription($request->get("description"));

        $em->persist($reclamation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);
        return new JsonResponse("Reclamation a ete modifiee avec success.");

    }



    /******************affichage Reclamation*****************************************/

    /**
     * @Route("/displayReclamations", name="display_reclamation")
     */
    public function allRecAction()
    {

        $reclamation = $this->getDoctrine()->getManager()->getRepository(Reclamation::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);

        return new JsonResponse($formatted);

    }


    /******************Detail Reclamation*****************************************/

    /**
     * @Route("/detailReclamation", name="detail_reclamation")
     * @Method("GET")
     */

    //Detail Reclamation
    public function detailReclamationAction(Request $request)
    {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getManager()->getRepository(Reclamation::class)->find($id);
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getDescription();
        });
        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($reclamation);
        return new JsonResponse($formatted);
    }


}