<?php
namespace App\Enums;

enum CategoriaServidor: string
{
    case SAUDE        = 'saude';
    case MAGISTERIO   = 'magisterio';
    case GUARDA       = 'guarda';
    case QUADRO_GERAL = 'quadro_geral';

    public function label(): string
    {
        return match($this) {
            self::SAUDE        => 'Profissionais da Saúde',
            self::MAGISTERIO   => 'Profissionais do Magistério',
            self::GUARDA       => 'Guarda Municipal',
            self::QUADRO_GERAL => 'Quadro Geral',
        };
    }

    public function anexo(): string
    {
        return match($this) {
            self::QUADRO_GERAL => 'ANEXO I',
            self::SAUDE        => 'ANEXO II',
            self::GUARDA       => 'ANEXO III',
            self::MAGISTERIO   => 'ANEXO IV',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($c) => [$c->value => $c->label()])
            ->toArray();
    }
}
