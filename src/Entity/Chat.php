<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Chat
 *
 * @ORM\Table(name="chat", indexes={@ORM\Index(name="id_sender", columns={"id_sender"})})
 * @ORM\Entity
 */
class Chat
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_chat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idChat;

    /**
     * @var string
     *@Assert\NotBlank(message="L'image  is required")
     * @ORM\Column(name="message", type="string", length=355, nullable=false)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="envoyeAt", type="date", nullable=false)
     */
    private $envoyeat;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_sender", referencedColumnName="CIN")
     * })
     */
    private $idSender;


    public function getIdChat(): ?int
    {
        return $this->idChat;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getEnvoyeat(): ?\DateTimeInterface
    {
        return $this->envoyeat;
    }

    public function setEnvoyeat(\DateTimeInterface $envoyeat): self
    {
        $this->envoyeat = $envoyeat;

        return $this;
    }

    public function getIdSender(): ?User
    {
        return $this->idSender;
    }

    public function setIdSender(?User $idSender): self
    {
        $this->idSender = $idSender;

        return $this;
    }


}
