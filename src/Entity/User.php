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
class User implements UserInterface
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
     * @param int $cin
     */
    public function setCin(int $cin): void
    {
        $this->cin = $cin;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return \DateTime
     */
    public function getDatedenaissance()
    {
        return $this->datedenaissance;
    }

    /**
     * @param \DateTime $datedenaissance
     */
    public function setDatedenaissance(\DateTime $datedenaissance): void
    {
        $this->datedenaissance = $datedenaissance;
    }

    /**
     * @return int
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param int $telephone
     */
    public function setTelephone(int $telephone): void
    {
        $this->telephone = $telephone;
    }


    /**
     * @return string
     */
    public function getEtat()
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

    /**
     * @return string|null
     */
    public function getAdressHost()
    {
        return $this->adressHost;
    }

    /**
     * @param string|null $adressHost
     */
    public function setAdressHost(?string $adressHost): void
    {
        $this->adressHost = $adressHost;
    }

    /**
     * @return string|null
     */
    public function getImageCin()
    {
        return $this->imageCin;
    }

    /**
     * @param string|null $imageCin
     */
    public function setImageCin(?string $imageCin): void
    {
        $this->imageCin = $imageCin;
    }

    /**
     * @return string
     */
    public function getImageProfile()
    {
        return $this->imageProfile;
    }

    /**
     * @param string $imageProfile
     */
    public function setImageProfile(string $imageProfile): void
    {
        $this->imageProfile = $imageProfile;
    }


    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
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
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }


    public function getUsername()
    {
        return (string) $this->email;

    }

    /**
     * @return string
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * @param string $googleId
     */
    public function setGoogleId(string $googleId): void
    {
        $this->googleId = $googleId;
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param string $facebookId
     */
    public function setFacebookId(string $facebookId): void
    {
        $this->facebookId = $facebookId;
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
