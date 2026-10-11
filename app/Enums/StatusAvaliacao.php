<?php
namespace App\Enums;

enum StatusAvaliacao: string
{
    case PENDENTE   = 'pendente';
    case PREENCHIDA = 'preenchida';
    case NOTIFICADA = 'notificada';
    case EM_DEFESA  = 'em_defesa';
    case CONCLUIDA  = 'concluida';
    case CANCELADA  = 'cancelada';

    public function label(): string
    {
        return match($this) {
            self::PENDENTE   => 'Pendente',
            self::PREENCHIDA => 'Preenchida',
            self::NOTIFICADA => 'Notificada',
            self::EM_DEFESA  => 'Em defesa',
            self::CONCLUIDA  => 'Concluída',
            self::CANCELADA  => 'Cancelada',
        };
    }

    public function cor(): string
    {
        return match($this) {
            self::PENDENTE   => 'warning',
            self::PREENCHIDA => 'info',
            self::NOTIFICADA => 'primary',
            self::EM_DEFESA  => 'danger',
            self::CONCLUIDA  => 'success',
            self::CANCELADA  => 'gray',
        };
    }
}
