<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="fk_off", columns={"idoffre"}), @ORM\Index(name="fk_cin", columns={"idguest"})})
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
     * @var int
     *
     * @ORM\Column(name="idevent", type="integer", nullable=false)
     */
    private $idevent;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idguest", referencedColumnName="CIN")
     * })
     */
    private $idguest;

    /**
     * @var \Offres
     *
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

    public function getIdevent(): ?int
    {
        return $this->idevent;
    }

    public function setIdevent(int $idevent): self
    {
        $this->idevent = $idevent;

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
