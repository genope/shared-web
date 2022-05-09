<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="fk_idU", columns={"idUser"})})
 * @ORM\Entity
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @Groups("post:read")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message = "Veuillez transmettre le type de votre rélamation.")
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     * @Groups("post:read")
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="date", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     * @Groups("post:read")
     */
    private $datecreation = 'CURRENT_TIMESTAMP';
    function __construct(){
        $this->datecreation= new \DateTime();
    }
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dateTraitement", type="date", nullable=true)
     * @Groups("post:read")
     */
    private $datetraitement;


    /**
     * @var string
     *@Assert\NotBlank(message = "L'objet ne doit pas être vide.")
     * @Assert\Length(
     *     min=3 , max=255,
     *     minMessage="l'objet doit comporter au moins 3 caractéres" ,
     *     maxMessage="l'objet doit comporter au plus 255 caractéres"  )
     * @ORM\Column(name="objet", type="string", length=255, nullable=false)
     * @Groups("post:read")
     */
    private $objet;

    /**
     * @var string
     *@Assert\NotBlank(message = "La description ne doit pas être vide.")
     * @Assert\Length(
     *     min=3 ,
     *     minMessage="l'adresse de la publication doit comporter au moins 3 caractéres")
     * @ORM\Column(name="description", type="string", length=5000, nullable=false)
     * @Groups("post:read")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=20, nullable=false)
     * @Groups("post:read")
     */
    private $statut;

    /**
     * @var string
     * @Assert\Email(
     *     message = "L'Email '{{ value }}' n'est pas valide."
     * )
     * @ORM\Column(name="email", type="string", length=40, nullable=false)
     * @Groups("post:read")
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @Groups("post:read")
     */
    private $image;

    /**
     * @var string
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     * @Groups("post:read")
     */
    private $nom;

    /**
     * @var string
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     * @Groups("post:read")
     */
    private $prenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="vocal", type="string", length=255, nullable=true)
     * @Groups("post:read")
     */
    private $vocal;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="CIN")
     * })
     * @Groups("post:read")
     */
    private $iduser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function getDatetraitement(): ?\DateTimeInterface
    {
        return $this->datetraitement;
    }

    public function setDatetraitement(?\DateTimeInterface $datetraitement): self
    {
        $this->datetraitement = $datetraitement;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getVocal(): ?string
    {
        return $this->vocal;
    }

    public function setVocal(?string $vocal): self
    {
        $this->vocal = $vocal;

        return $this;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }


}

