<?php

namespace App\Entity;

use App\Enum\StatutDemande;
use App\Repository\MessageContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageContactRepository::class)]
class MessageContact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'Merci d\'indiquer votre nom.')]
    #[Assert\Length(min: 2, max: 150)]
    private ?string $nom = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Merci d\'indiquer une adresse email.')]
    #[Assert\Email(message: 'Cette adresse email n\'est pas valide.')]
    #[Assert\Length(max: 180, maxMessage: 'L\'adresse email ne doit pas dépasser {{ limit }} caractères.')]
    private ?string $email = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\Regex(
        pattern: '/^[0-9+\s().-]{8,20}$/',
        message: 'Ce numéro de téléphone n\'est pas valide.'
    )]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Merci d\'écrire votre message.')]
    #[Assert\Length(min: 10, max: 5000)]
    private ?string $message = null;

    #[ORM\Column]
    #[Assert\IsTrue(message: 'Vous devez accepter que vos informations soient utilisées pour vous répondre.')]
    private bool $consentement = false;

    #[ORM\Column(length: 20, enumType: StatutDemande::class)]
    private StatutDemande $statut;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(options: ['default' => false])]
    private bool $emailEnvoye = false;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->statut = StatutDemande::Nouvelle;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function isConsentement(): bool
    {
        return $this->consentement;
    }

    public function setConsentement(bool $consentement): static
    {
        $this->consentement = $consentement;

        return $this;
    }

    public function getStatut(): StatutDemande
    {
        return $this->statut;
    }

    public function setStatut(StatutDemande $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isEmailEnvoye(): bool
    {
        return $this->emailEnvoye;
    }

    public function setEmailEnvoye(bool $emailEnvoye): static
    {
        $this->emailEnvoye = $emailEnvoye;

        return $this;
    }
}