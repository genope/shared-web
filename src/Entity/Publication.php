<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Publication
 *
 * @ORM\Table(name="publication", indexes={@ORM\Index(name="fk_region", columns={"region_id"}), @ORM\Index(name="fk_user_pub", columns={"id_guest"})})
 * @ORM\Entity
 */
class Publication
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *@Assert\NotBlank(message="Veuillez entrer le nom")
     * @Assert\Length(
     *     min=2 , max=25,
     *     minMessage="le nom de la publication doit comporter au moins 2 caractéres" ,
     *     maxMessage="le nom de la publication doit comporter au plus 25 caractéres" )
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *@Assert\NotBlank(message="Veuillez entrer une description")
     * @Assert\Length(
     *     min=3 , max=255,
     *     minMessage="la description de la publication doit comporter au moins 3 caractéres" ,
     *     maxMessage="la description de la publication doit comporter au plus 255 caractéres" )
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     *
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     *
     */
    private $image;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez entrer l'adresse")
     *@Assert\Length(
     *     min=3 , max=255,
     *     minMessage="l'adresse de la publication doit comporter au moins 3 caractéres" ,
     *     maxMessage="l'adresse de la publication doit comporter au plus 255 caractéres"  )
     * @ORM\Column(name="adresse", type="string", length=70, nullable=false)
     *
     */
    private $adresse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecreation", type="date", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $datecreation = 'CURRENT_TIMESTAMP';
    function __construct() {
        $this->datecreation=new \DateTime();
    }

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_guest", referencedColumnName="CIN")
     * })
     */
    private $idGuest;

    /**
     * @var \Region
     *
     * @ORM\ManyToOne(targetEntity="Region")
     *
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     * })
     */
    private $region;

    protected $captchaCode;

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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

    public function getIdGuest(): ?User
    {
        return $this->idGuest;
    }

    public function setIdGuest(?User $idGuest): self
    {
        $this->idGuest = $idGuest;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }



}
