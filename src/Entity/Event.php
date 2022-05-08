<?php

namespace App\Entity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")

 */
class Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="idevent", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idevent;

    /**
     * @var string
     *
     * @ORM\Column(name="nomevent", type="string", length=24, nullable=false)
     *@Assert\NotBlank(message="Nom requis")
     *@Assert\Length(min="6",       minMessage= "Le nom doit contenir au minimum 6 caractères."
     *)

     */
    private $nomevent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedebev", type="date", nullable=false)
     *
     *@Assert\NotBlank(message="veuillez saisir une date")
     * @Assert\GreaterThan("today")
     */
    private $datedebev;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datefinev", type="date", nullable=false)
     * @Assert\NotBlank(message="veuillez saisir une date de fin valide ")
     * @Assert\GreaterThan(propertyPath="datedebev")
     */
    private $datefinev;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Upload a picture")
     * @Assert\File(mimeTypes={ "image/jpeg" ,"image/jpg","image/png","image/tiff" , "image/svg+xml"})
     * @Assert\Image(
    minWidth = 600,
    minWidthMessage="the image width is too small
    Minimum width expected is {{ min_width }}px " *)
     *     maxHeight=2000,
     *     allowLandscape = true,
     *     allowPortrait = true
     * )
     */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(name="nbparticip", type="integer", nullable=false)
     *@Assert\NotBlank(message="entrez un nombre supérieur à 20 requis")
     *@Assert\GreaterThan(20)
     */
    private $nbparticip;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=100, nullable=false)
     *@Assert\NotBlank(message="Description requise")
     *@Assert\Length(min="10",minMessage= "La description doit contenir au minimum 10 caractères."
     *)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=50, nullable=false)
     *@Assert\NotBlank(message="Lieu requis")
     *@Assert\Length(min="4",minMessage= "Le lieu doit contenir au minimum 4 caractères."
     *)
     */
    private $lieu;

    public function getIdevent(): ?int
    {
        return $this->idevent;
    }
    public function __toString() {
        return $this->nomevent;
    }

    public function getNomevent(): ?string
    {
        return $this->nomevent;
    }

    public function setNomevent(string $nomevent): self
    {
        $this->nomevent = $nomevent;

        return $this;
    }

    public function getDatedebev(): ?\DateTimeInterface
    {
        return $this->datedebev;
    }

    public function setDatedebev(\DateTimeInterface $datedebev): self
    {
        $this->datedebev = $datedebev;

        return $this;
    }

    public function getDatefinev(): ?\DateTimeInterface
    {
        return $this->datefinev;
    }

    public function setDatefinev(\DateTimeInterface $datefinev): self
    {
        $this->datefinev = $datefinev;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage( $image)
    {
        $this->image = $image;

        return $this;
    }

    public function getNbparticip(): ?int
    {
        return $this->nbparticip;
    }

    public function setNbparticip(int $nbparticip): self
    {
        $this->nbparticip = $nbparticip;

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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }


}
