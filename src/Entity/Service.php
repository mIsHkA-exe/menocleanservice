<?php

namespace App\Entity;

use App\Enum\CategorieService;
use App\Repository\ServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[UniqueEntity(fields: ['slug'], message: 'Ce slug est déjà utilisé par un autre service.')]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'Le nom du service est obligatoire.')]
    #[Assert\Length(min: 3, max: 150)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description est obligatoire.')]
    private ?string $description = null;

    /**
     * Identifiant de l'icône dans le sprite SVG (ex. "icon-building").
     */
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $icone = null;

    #[ORM\Column(length: 30, enumType: CategorieService::class)]
    #[Assert\NotNull(message: 'La catégorie est obligatoire.')]
    private ?CategorieService $categorie = null;

    #[ORM\Column(length: 160, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
        message: 'Le slug ne peut contenir que des minuscules, des chiffres et des tirets.'
    )]
    private ?string $slug = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['default' => 0])]
    #[Assert\PositiveOrZero]
    private int $position = 0;

    #[ORM\Column(options: ['default' => true])]
    private bool $estActif = true;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(?string $icone): static
    {
        $this->icone = $icone;

        return $this;
    }

    public function getCategorie(): ?CategorieService
    {
        return $this->categorie;
    }

    public function setCategorie(CategorieService $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function isEstActif(): bool
    {
        return $this->estActif;
    }

    public function setEstActif(bool $estActif): static
    {
        $this->estActif = $estActif;

        return $this;
    }

    /**
     * Utilisé par le champ <select> du formulaire de devis pour afficher le service.
     */
    public function __toString(): string
    {
        return $this->nom ?? '';
    }
}