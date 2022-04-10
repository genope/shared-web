<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit", uniqueConstraints={@ORM\UniqueConstraint(name="ref_prod", columns={"ref_prod"})}, indexes={@ORM\Index(name="nomCategorie", columns={"nomCategorie"}), @ORM\Index(name="c2", columns={"region"})})
 * @ORM\Entity
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_prod", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProd;

    /**
     * @var string
     *
     * @ORM\Column(name="ref_prod", type="string", length=25, nullable=false)
     */
    private $refProd;

    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="string", length=255, nullable=false)
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
     */
    private $image;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="qte_stock", type="integer", nullable=false)
     */
    private $qteStock;

    /**
     * @var \Region
     *
     * @ORM\ManyToOne(targetEntity="Region")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region", referencedColumnName="nom")
     * })
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
