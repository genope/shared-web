<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\ComplexPassword;


/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="user_email", columns={"Email"})})
 * @ORM\Entity
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="CIN", type="integer", nullable=false)
     * @Assert\Length(min="8",max="8")
     * @ORM\Id
     *
     */
    private $cin;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="Nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *@Assert\NotBlank
     * @ORM\Column(name="Prenom", type="string", length=255, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Email()
     * @ORM\Column(name="Email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length(min="6")
     * @ComplexPassword()
     * @ORM\Column(name="Password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var \DateTime
     * @Assert\NotBlank
     * @ORM\Column(name="dateDeNaissance", type="date", nullable=false)
     */
    private $datedenaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="googleId", type="string", length=255, nullable=true)
     */
    private $googleId;
    /**
     * @var string
     *
     * @ORM\Column(name="facebookId", type="string", length=255, nullable=true)
     */
    private $facebookId;

    /**
     * @var int
     * @Assert\NotBlank(groups={"Registration"})
     * @Assert\Length(min="8",max="8")d
     * @ORM\Column(name="Telephone", type="integer", nullable=false)
     */
    private $telephone;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="json")

     */
    private $etat= [];

    /**
     * @var string|null
     *
     * @ORM\Column(name="adress_host", type="string", length=255, nullable=true)
     */
    private $adressHost;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image_cin", type="string", length=255, nullable=true)
     */
    private $imageCin;

    /**

     * @ORM\Column(type="boolean")
     */
    private $is_verified =false ;

    /**
     * @var string

     *
     * @ORM\Column(name="image_profile", type="string", length=255, nullable=true)
     */
    private $imageProfile;

    /**

     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reset_token;

    /**
     * @return int
     */
    public function getCin(): ?int
    {
        return $this->cin;
    }


    /**
     * @var string|null
     *
     * @ORM\Column(name="googleId", type="string", length=255, nullable=true)
     */
    private $googleid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="facebookId", type="string", length=255, nullable=true)
     */
    private $facebookid;


    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }



    public function getIsVerified() : bool
    {
        return $this->is_verified;
    }


    public function setIsVerified(bool $is_verified):self
    {
        $this->is_verified = $is_verified;
        return $this;
    }


    /**
     * @param string $nom
     */
    public function setNom($nom)

    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getDatedenaissance(): ?\DateTimeInterface
    {
        return $this->datedenaissance;
    }

    public function setDatedenaissance(\DateTimeInterface $datedenaissance): self
    {
        $this->datedenaissance = $datedenaissance;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEtat(): ?array
    {
        $Etat = $this->etat;
        // guarantee every user at least has ROLE_USER
        $Etat[] = 'Approved';

        return array_unique($Etat);
    }


    /**
     * @param string $etat
     */
    public function setEtat(array $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getAdressHost(): ?string
    {
        return $this->adressHost;
    }

    public function setAdressHost(?string $adressHost): self
    {
        $this->adressHost = $adressHost;

        return $this;
    }

    public function getImageCin(): ?string
    {
        return $this->imageCin;
    }

    public function setImageCin(?string $imageCin): self
    {
        $this->imageCin = $imageCin;

        return $this;
    }

    public function getImageProfile(): ?string
    {
        return $this->imageProfile;
    }

    public function setImageProfile(?string $imageProfile): self
    {
        $this->imageProfile = $imageProfile;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function __toString()
    {
        return $this->roles;
    }
    /**
     * @see UserInterface
     */
    public function getPassword(): string

    {
        return $this->googleid;
    }

    public function setGoogleid(?string $googleid): self
    {
        $this->googleid = $googleid;

        return $this;
    }

    public function getFacebookid(): ?string
    {
        return $this->facebookid;
    }

    public function setFacebookid(?string $facebookid): self
    {
        $this->facebookid = $facebookid;

        return $this;
    }



    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

}
