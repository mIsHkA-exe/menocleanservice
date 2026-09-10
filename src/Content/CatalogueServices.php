<?php

declare(strict_types=1);

namespace App\Content;

use App\Enum\CategorieService;

/**
 * Catalogue de référence des prestations.
 *
 * Source unique : la commande de peuplement lit ce fichier.
 * Le "slug" est la clé métier : il identifie un service d'une base à l'autre.
 */
final class CatalogueServices
{
    /**
     * @var list<array{
     *     slug: string,
     *     nom: string,
     *     description: string,
     *     icone: string|null,
     *     categorie: CategorieService,
     *     position: int
     * }>
     */
    private const SERVICES = [
        [
            'nom' => 'Nettoyage de copropriétés',
            'slug' => 'nettoyage-coproprietes',
            'icone' => 'icon-building',
            'categorie' => CategorieService::Coproprietes,
            'position' => 10,
            'description' => "Entretien régulier des parties communes de votre copropriété : halls, escaliers, ascenseurs et paliers, pour un cadre de vie propre et valorisé au quotidien.",
        ],
        [
            'nom' => "Nettoyage d'immeubles",
            'slug' => 'nettoyage-immeubles',
            'icone' => 'icon-building2',
            'categorie' => CategorieService::Coproprietes,
            'position' => 20,
            'description' => "Nettoyage complet d'immeubles résidentiels ou tertiaires : façades accessibles, halls d'entrée et circulations, pour une image soignée en toute saison.",
        ],
        [
            'nom' => 'Entretien de bureaux',
            'slug' => 'entretien-bureaux',
            'icone' => 'icon-briefcase',
            'categorie' => CategorieService::Bureaux,
            'position' => 30,
            'description' => "Nettoyage de bureaux sur mesure : postes de travail, sols, sanitaires et espaces communs, pour un environnement professionnel sain et accueillant.",
        ],
        [
            'nom' => 'Nettoyage de commerces',
            'slug' => 'nettoyage-commerces',
            'icone' => 'icon-briefcase',
            'categorie' => CategorieService::Bureaux,
            'position' => 40,
            'description' => "Boutiques, showrooms et surfaces commerciales : un entretien adapté aux horaires d'ouverture pour présenter un espace de vente irréprochable à vos clients.",
        ],
        [
            'nom' => 'Nettoyage après travaux',
            'slug' => 'nettoyage-apres-travaux',
            'icone' => 'icon-gear',
            'categorie' => CategorieService::Chantiers,
            'position' => 50,
            'description' => "Nettoyage après chantier pour éliminer poussières, résidus et traces de peinture, et rendre le lieu immédiatement habitable ou exploitable.",
        ],
        [
            'nom' => 'Nettoyage de fin de chantier',
            'slug' => 'nettoyage-fin-de-chantier',
            'icone' => 'icon-check',
            'categorie' => CategorieService::Chantiers,
            'position' => 60,
            'description' => "Prestation complète de nettoyage de fin de chantier avant livraison : sols, vitres, menuiseries et surfaces, pour une réception sans réserve.",
        ],
        [
            'nom' => 'Remise en état',
            'slug' => 'remise-en-etat',
            'icone' => 'icon-sparkle',
            'categorie' => CategorieService::Chantiers,
            'position' => 70,
            'description' => "Remise en état de logements ou de locaux après sinistre, déménagement ou longue vacance, pour retrouver un espace sain et prêt à l'usage.",
        ],
        [
            'nom' => 'Nettoyage de vitres',
            'slug' => 'nettoyage-vitres',
            'icone' => 'icon-window',
            'categorie' => CategorieService::Sols,
            'position' => 80,
            'description' => "Nettoyage de vitres avec des techniques garantissant un résultat sans traces, durable dans le temps.",
        ],
        [
            'nom' => 'Nettoyage haute pression',
            'slug' => 'nettoyage-haute-pression',
            'icone' => 'icon-droplet',
            'categorie' => CategorieService::Exterieurs,
            'position' => 90,
            'description' => "Traitement haute pression des façades, terrasses et abords extérieurs pour éliminer mousses, salissures incrustées et dépôts tenaces.",
        ],
        [
            'nom' => 'Décapage des sols',
            'slug' => 'decapage-des-sols',
            'icone' => 'icon-layers',
            'categorie' => CategorieService::Sols,
            'position' => 100,
            'description' => "Décapage professionnel des sols durs pour retirer les anciennes couches d'usure et préparer la surface à un traitement de protection neuf.",
        ],
        [
            'nom' => 'Cristallisation des marbres',
            'slug' => 'cristallisation-des-marbres',
            'icone' => 'icon-star',
            'categorie' => CategorieService::Sols,
            'position' => 110,
            'description' => "Cristallisation des sols en marbre et pierre naturelle pour raviver la brillance, renforcer la résistance et sublimer vos espaces de réception.",
        ],
        [
            'nom' => 'Shampooing moquettes',
            'slug' => 'shampooing-moquettes',
            'icone' => 'icon-flask',
            'categorie' => CategorieService::Sols,
            'position' => 120,
            'description' => "Shampooing en profondeur des moquettes et textiles de sol pour éliminer taches, odeurs et acariens, dans les bureaux comme dans les parties communes.",
        ],
        [
            'nom' => 'Désinfection',
            'slug' => 'desinfection',
            'icone' => 'icon-shield',
            'categorie' => CategorieService::Bureaux,
            'position' => 130,
            'description' => "Désinfection des surfaces et points de contact selon les protocoles en vigueur, pour des locaux professionnels sains et sécurisés pour tous.",
        ],
        [
            'nom' => 'Débarras et enlèvement des encombrants',
            'slug' => 'debarras-encombrants',
            'icone' => 'icon-trash',
            'categorie' => CategorieService::Exterieurs,
            'position' => 140,
            'description' => "Débarras de locaux, caves ou parties communes et enlèvement des encombrants, avec tri et évacuation dans le respect des filières de traitement.",
        ],
        [
            'nom' => 'Nettoyage des parkings',
            'slug' => 'nettoyage-parkings',
            'icone' => 'icon-car',
            'categorie' => CategorieService::Exterieurs,
            'position' => 150,
            'description' => "Entretien des parkings, box et sous-sols de copropriété ou d'entreprise : dégraissage des sols, dépoussiérage et évacuation des déchets.",
        ],
        [
            'nom' => 'Entretien des espaces communs',
            'slug' => 'entretien-espaces-communs',
            'icone' => 'icon-users',
            'categorie' => CategorieService::Coproprietes,
            'position' => 160,
            'description' => "Entretien courant des espaces communs : halls, ascenseurs, locaux poubelles et abords, pour un immeuble propre et accueillant toute l'année.",
        ],
        [
            'nom' => 'Sortie et rentrée des poubelles',
            'slug' => 'sortie-rentree-poubelles',
            'icone' => 'icon-clock',
            'categorie' => CategorieService::Coproprietes,
            'position' => 170,
            'description' => "Gestion de la sortie et de la rentrée des poubelles aux jours de collecte, avec nettoyage des containers et des locaux dédiés.",
        ],
        [
            'nom' => 'Nettoyage des locaux professionnels',
            'slug' => 'nettoyage-locaux-professionnels',
            'icone' => 'icon-building',
            'categorie' => CategorieService::Bureaux,
            'position' => 180,
            'description' => "Entreprise de propreté généraliste pour le nettoyage de locaux professionnels de toute taille, avec un plan d'entretien adapté à votre activité.",
        ],
    ];

    /**
     * @return list<array{slug: string, nom: string, description: string, icone: string|null, categorie: CategorieService, position: int}>
     */
    public static function all(): array
    {
        return self::SERVICES;
    }
}