<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panierdetails
 *
 * @ORM\Table(name="panierdetails", indexes={@ORM\Index(name="idProduit", columns={"idProduit"}), @ORM\Index(name="idCommande", columns={"idCommande"})})
 * @ORM\Entity
 */
class Panierdetails
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
     *
     * @ORM\Column(name="idProduit", type="string", length=50, nullable=false)
     */
    private $idproduit;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var \Panier
     *
     * @ORM\ManyToOne(targetEntity="Panier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCommande", referencedColumnName="id_panier")
     * })
     */
    private $idcommande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdproduit(): ?string
    {
        return $this->idproduit;
    }

    public function setIdproduit(string $idproduit): self
    {
        $this->idproduit = $idproduit;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

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

    public function getIdcommande(): ?Panier
    {
        return $this->idcommande;
    }

    public function setIdcommande(?Panier $idcommande): self
    {
        $this->idcommande = $idcommande;

        return $this;
    }


}
