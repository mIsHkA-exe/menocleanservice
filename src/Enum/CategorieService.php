<?php

namespace App\Enum;

enum CategorieService: string
{
    case Coproprietes = 'coproprietes';
    case Bureaux = 'bureaux';
    case Chantiers = 'chantiers';
    case Sols = 'sols';
    case Exterieurs = 'exterieurs';

    /**
     * Libellé affiché sur les boutons de filtre.
     */
    public function label(): string
    {
        return match ($this) {
            self::Coproprietes => 'Copropriétés & immeubles',
            self::Bureaux => 'Bureaux & commerces',
            self::Chantiers => 'Chantiers & rénovation',
            self::Sols => 'Sols & surfaces',
            self::Exterieurs => 'Extérieurs & parkings',
        };
    }
}