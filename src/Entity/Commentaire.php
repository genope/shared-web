<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="fk_comment_pubb", columns={"id_publication"}), @ORM\Index(name="fk_comment_user", columns={"id_guest"})})
 * @ORM\Entity
 */
class Commentaire
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
     * @Assert\NotBlank(message="Veuillez entrer l'objet")
     *@Assert\Length(
     *     min=3 , max=255,
     *     minMessage="l'objet de la publication doit comporter au moins 3 caractéres" ,
     *     maxMessage="l'objet de la publication doit comporter au plus 255 caractéres"  )
     * @ORM\Column(name="nom", type="string", length=20, nullable=false)
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez entrer votre commentaire")
     *@Assert\Length(
     *     min=3 , max=255,
     *     minMessage="le commentaire de la publication doit comporter au moins 3 caractéres" ,
     *     maxMessage="le commentaire de la publication doit comporter au plus 255 caractéres"  )
     * @ORM\Column(name="comment", type="text", length=0, nullable=false)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_com", type="date", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateCom = 'CURRENT_TIMESTAMP';
    function __construct() {
        $this->dateCom=new \DateTime();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer", nullable=false)
     */
    private $note;

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
     * @var \Publication
     *
     * @ORM\ManyToOne(targetEntity="Publication")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_publication", referencedColumnName="id")
     * })
     */
    private $idPublication;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDateCom(): ?\DateTimeInterface
    {
        return $this->dateCom;
    }

    public function setDateCom(\DateTimeInterface $dateCom): self
    {
        $this->dateCom = $dateCom;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

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

    public function getIdPublication(): ?Publication
    {
        return $this->idPublication;
    }

    public function setIdPublication(?Publication $idPublication): self
    {
        $this->idPublication = $idPublication;

        return $this;
    }


}
