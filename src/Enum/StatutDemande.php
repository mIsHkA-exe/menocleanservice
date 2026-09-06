<?php

namespace App\Enum;

enum StatutDemande: string
{
    case Nouvelle = 'nouvelle';
    case EnCours = 'en_cours';
    case Traitee = 'traitee';
    case Archivee = 'archivee';

    public function label(): string
    {
        return match ($this) {
            self::Nouvelle => 'Nouvelle',
            self::EnCours => 'En cours de traitement',
            self::Traitee => 'Traitée',
            self::Archivee => 'Archivée',
        };
    }
}