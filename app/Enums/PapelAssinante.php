<?php
namespace App\Enums;

enum PapelAssinante: string
{
    case CHEFIA   = 'chefia';
    case AVALIADO = 'avaliado';
    case COMPAQ   = 'compaq';

    public function label(): string
    {
        return match($this) {
            self::CHEFIA   => 'Chefia',
            self::AVALIADO => 'Avaliado',
            self::COMPAQ   => 'Membro da COMPAQ',
        };
    }
}
