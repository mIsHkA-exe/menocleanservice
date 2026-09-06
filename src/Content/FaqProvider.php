<?php

namespace App\Content;

/**
 * Source unique des questions fréquentes.
 *
 * Alimente à la fois l'affichage HTML et les données structurées Schema.org :
 * les deux ne peuvent plus diverger.
 *
 * Candidat naturel à un passage en base le jour où l'entreprise voudra gérer
 * sa FAQ elle-même — seule cette classe changerait.
 */
final class FaqProvider
{
    private const QUESTIONS = [
        [
            'question' => 'Quels types de bâtiments nettoyez-vous ?',
            'reponse' => "Notre entreprise de nettoyage intervient auprès des copropriétés, immeubles résidentiels et tertiaires, bureaux, commerces et chantiers de construction ou de rénovation, partout en Île-de-France.",
        ],
        [
            'question' => 'Le devis est-il vraiment gratuit et sans engagement ?',
            'reponse' => "Oui. Chaque demande de devis est étudiée gratuitement et vous êtes entièrement libre d'accepter ou non notre proposition, sans aucun engagement de votre part.",
        ],
        [
            'question' => 'Intervenez-vous en urgence ou le week-end ?',
            'reponse' => "Notre équipe reste joignable 7j/7 pour les demandes urgentes (dégât des eaux, remise en état avant un événement, fin de chantier imprévue) et peut intervenir en dehors des horaires classiques sur devis.",
        ],
        [
            'question' => "Vos produits sont-ils respectueux de l'environnement ?",
            'reponse' => "Nous privilégions dès que possible des produits professionnels éco-responsables, adaptés à chaque surface, dans une démarche de nettoyage plus respectueuse de l'environnement.",
        ],
        [
            'question' => 'Êtes-vous assurés pour intervenir dans notre copropriété ou notre entreprise ?',
            'reponse' => "Oui, MENO CLEAN SERVICES dispose d'une assurance responsabilité civile professionnelle couvrant l'ensemble de nos interventions chez les particuliers, copropriétés et entreprises.",
        ],
        [
            'question' => 'Quelle est la différence entre nettoyage après travaux et fin de chantier ?',
            'reponse' => "Le nettoyage après travaux vise à retirer les poussières et résidus au fil du chantier, tandis que le nettoyage de fin de chantier est une remise en état complète juste avant la livraison du bien, pour une réception sans réserve.",
        ],
    ];

    /**
     * @return array<int, array{question: string, reponse: string}>
     */
    public function all(): array
    {
        return self::QUESTIONS;
    }
}