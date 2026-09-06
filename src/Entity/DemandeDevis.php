<?php

namespace App\Entity;

use App\Enum\StatutDemande;
use App\Repository\DemandeDevisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DemandeDevisRepository::class)]
class DemandeDevis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'Merci d\'indiquer votre nom.')]
    #[Assert\Length(min: 2, max: 150)]
    private ?string $nom = null;

    #[ORM\Column(length: 150, nullable: true)]
    #[Assert\Length(max: 150)]
    private ?string $societe = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: 'Merci d\'indiquer un numéro de téléphone.')]
    #[Assert\Regex(
        pattern: '/^[0-9+\s().-]{8,20}$/',
        message: 'Ce numéro de téléphone n\'est pas valide.'
    )]
    private ?string $telephone = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Merci d\'indiquer une adresse email.')]
    #[Assert\Email(message: 'Cette adresse email n\'est pas valide.')]
    #[Assert\Length(max: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Merci d\'indiquer l\'adresse d\'intervention.')]
    private ?string $adresseIntervention = null;

    /**
     * Lien vivant vers le service demandé.
     * Volontairement nullable : si le service est supprimé, la demande subsiste.
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Service $service = null;

    /**
     * Photographie figée du libellé au moment de la demande.
     * Ne doit JAMAIS être modifiée après création.
     */
    #[ORM\Column(length: 150)]
    #[Assert\NotBlank]
    private ?string $prestationLibelle = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message: 'La surface doit être un nombre positif.')]
    #[Assert\LessThan(1000000)]
    private ?int $surface = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 5000)]
    private ?string $message = null;

    #[ORM\Column]
    #[Assert\IsTrue(message: 'Vous devez accepter que vos informations soient utilisées pour être recontacté.')]
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

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(?string $societe): static
    {
        $this->societe = $societe;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

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

    public function getAdresseIntervention(): ?string
    {
        return $this->adresseIntervention;
    }

    public function setAdresseIntervention(string $adresseIntervention): static
    {
        $this->adresseIntervention = $adresseIntervention;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    /**
     * Enregistre le service ET fige son libellé au moment de la demande.
     */
    public function setService(?Service $service): static
    {
        $this->service = $service;

        if (null !== $service && null === $this->prestationLibelle) {
            $this->prestationLibelle = (string) $service;
        }

        return $this;
    }

    public function getPrestationLibelle(): ?string
    {
        return $this->prestationLibelle;
    }

    public function setPrestationLibelle(string $prestationLibelle): static
    {
        $this->prestationLibelle = $prestationLibelle;

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(?int $surface): static
    {
        $this->surface = $surface;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
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