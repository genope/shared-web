<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="fk_reservation_event", columns={"idevent"}), @ORM\Index(name="fk_reservation_guest", columns={"idguest"}), @ORM\Index(name="fk_reservation_offre", columns={"idoffre"})})
 * @ORM\Entity
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="idreserv", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idreserv;

    /**
     * @var \DateTime|null
     *
     * @Assert\NotBlank(message="veuillez entrez une date ")
     * @Assert\GreaterThan("today")

     * @ORM\Column(name="datedebut", type="date", nullable=true)
     */
    private $datedebut;

    /**
     * @var \DateTime|null
     * @Assert\NotBlank(message="veuillez entrez une date   ")
     * @Assert\GreaterThan(propertyPath="datedebut")

     * @ORM\Column(name="datefin", type="date", nullable=true)
     */
    private $datefin;

    /**
     * @var \User
     *
     * @Assert\NotBlank(message="veuillez saisir votre id  ")

     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idguest", referencedColumnName="CIN")
     * })
     */
    private $idguest;

    /**
     * @var \Event
     *
     * @Assert\NotBlank(message="veuillez saisir l'id de levent  ")

     * @ORM\ManyToOne(targetEntity="Event")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idevent", referencedColumnName="idevent")

     * })
     */
    private $idevent;

    /**
     * @var \Offres
     *
     * @Assert\NotBlank(message="veuillez saisir l'id de l'offre ")

     * @ORM\ManyToOne(targetEntity="Offres")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idoffre", referencedColumnName="id_offre")
     * })
     */
    private $idoffre;

    public function getIdreserv(): ?int
    {
        return $this->idreserv;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(?\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getIdguest(): ?User
    {
        return $this->idguest;
    }

    public function setIdguest(?User $idguest): self
    {
        $this->idguest = $idguest;

        return $this;
    }

    public function getIdevent(): ?Event
    {
        return $this->idevent;
    }

    public function setIdevent(?Event $idevent): self
    {
        $this->idevent = $idevent;

        return $this;
    }

    public function getIdoffre(): ?Offres
    {
        return $this->idoffre;
    }

    public function setIdoffre(?Offres $idoffre): self
    {
        $this->idoffre = $idoffre;

        return $this;
    }


}
