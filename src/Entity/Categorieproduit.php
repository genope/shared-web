<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorieproduit
 *
 * @ORM\Table(name="categorieproduit", uniqueConstraints={@ORM\UniqueConstraint(name="nomCategorie", columns={"nomCategorie"})})
 * @ORM\Entity
 */
class Categorieproduit
{
    /**
     * @var string
     *
     * @ORM\Column(name="nomCategorie", type="string", length=255, nullable=false)
     * @ORM\OneToMany(targetEntity="Produit" ,mappedBy="Categorieproduit")
     * @ORM\Id
     */
    private string $nomcategorie;

    public function getNomcategorie(): ?string
    {
        return $this->nomcategorie;
    }

    public function setNomcategorie(string $nomcategorie): self
    {
        $this->nomcategorie = $nomcategorie;

        return $this;
    }

    public function __toString()
    {
        return $this->nomcategorie;
    }
}
