<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;





/**
 * Produit
 *
 * @ORM\Table(name="produit", uniqueConstraints={@ORM\UniqueConstraint(name="ref_prod", columns={"ref_prod"})}, indexes={@ORM\Index(name="nomCategorie", columns={"nomCategorie"}), @ORM\Index(name="c2", columns={"region"})})
 * @ORM\Entity
 * @UniqueEntity("refProd",
 *     message="La reference est deja utilisé"
 * )
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_prod", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("produits")
     */
    private $idProd;

    /**
     * @var string
     *
     * @ORM\Column(name="ref_prod", type="string", length=25, nullable=false)
     * @Groups("produits")
     * @Assert\NotBlank(message="Ne doit pas etre vide")
     * @Assert\Length(min=4, max=25,
     *     minMessage ="Doit etre  moins que 25 caracteres", minMessage ="Doit etre plus que 4 caracteres")
     */
    private $refProd;

    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="string", length=255, nullable=false)
     * @Groups("produits")
     * @Assert\NotBlank(message="Ne doit pas etre vide")
     * @Assert\Length(min=4, max=50,
     *     minMessage ="Doit etre  moins que 25 caracteres", minMessage ="Doit etre plus que 4 caracteres")
     */
    private $designation;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     * @Groups("produits")
     * @Assert\NotBlank(message="Ne doit pas etre vide")
     * @Assert\Length(min=4, max=255)
     *  @Assert\File(
     *     maxSize = "2048k",
     *     mimeTypes = {"image/png", "image/jpeg"},
     *     mimeTypesMessage = "Veuillez Ajouter une image avec un format valide"
     * )
     */
    private $image;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     * @Groups("produits")
     * @Assert\NotBlank(message="Ne doit pas etre vide")
     * @Assert\Positive(message="doit etre Positif")
     * @Assert\Regex(
     *     pattern="/\d/",
     *     message="Le nom de la categorie ne peut pas contenir de lettres"
     * )
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="qte_stock", type="integer", nullable=false)
     * @Groups("produits")
     * @Assert\NotBlank(message="Ne doit pas etre vide")
     * @Assert\Positive(message="doit etre Positif")
     */
    private $qteStock;

    /**
     * @var \Region
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region", referencedColumnName="nom")
     * })
     * @Assert\NotBlank(message="Ne doit pas etre vide")
     */
    private $region;

    /**
     * @var \Categorieproduit
     *
     * @ORM\ManyToOne(targetEntity="Categorieproduit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nomCategorie", referencedColumnName="nomCategorie")
     * })
     */
    private $nomcategorie;

    public function getIdProd(): ?int
    {
        return $this->idProd;
    }

    public function getRefProd(): ?string
    {
        return $this->refProd;
    }

    public function setRefProd(string $refProd): self
    {
        $this->refProd = $refProd;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQteStock(): ?int
    {
        return $this->qteStock;
    }

    public function setQteStock(int $qteStock): self
    {
        $this->qteStock = $qteStock;

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

    public function getNomcategorie(): ?Categorieproduit
    {
        return $this->nomcategorie;
    }

    public function setNomcategorie(?Categorieproduit $nomcategorie): self
    {
        $this->nomcategorie = $nomcategorie;

        return $this;
    }


}
