<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorieproduit
 *
 * @ORM\Table(name="categorieproduit", uniqueConstraints={@ORM\UniqueConstraint(name="idCategorie", columns={"idCategorie"})})
 * @ORM\Entity
 */
class Categorieproduit
{
    /**
     * @var int
     *
     * @ORM\Column(name="idCategorie", type="integer", nullable=false)
     */
    private $idcategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="nomCategorie", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\OneToMany(targetEntity="Produit" ,mappedBy="Categorieproduit")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $nomcategorie;

    public function getIdcategorie(): ?int
    {
        return $this->idcategorie;
    }

    public function setIdcategorie(int $idcategorie): self
    {
        $this->idcategorie = $idcategorie;

        return $this;
    }

    public function getNomcategorie(): ?string
    {
        return $this->nomcategorie;
    }
    public function setnomcategorie(int $nomcategorie): self
    {
        $this->nomcategorie = $nomcategorie;

        return $this;
    }
    public function __toString()
    {
        return $this->nomcategorie;
    }

}
