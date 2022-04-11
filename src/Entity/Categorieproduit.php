<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Categorieproduit
 *
 * @ORM\Table(name="categorieproduit", uniqueConstraints={@ORM\UniqueConstraint(name="nomCategorie", columns={"nomCategorie"})})
 * @ORM\Entity
 * @UniqueEntity("nomcategorie",
 *     message="La categorie existe deja"
 * )
 */
class Categorieproduit
{
    /**
     * @var string
     *
     * @ORM\Column(name="nomCategorie", type="string", length=255, nullable=false)
     * @ORM\OneToMany(targetEntity="Produit" ,mappedBy="Categorieproduit")
     * @Assert\NotBlank(message="Ne doit pas etre vide")
     * @Assert\Length(min=4, max=50,
     *     minMessage ="Doit etre  moins que 25 caracteres", minMessage ="Doit etre plus que 4 caracteres")
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Lenom de la categorie ne peut pas contenir des chiffres"
     * )
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
