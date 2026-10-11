<?php
namespace App\Observers;

use App\Models\Avaliacao;
use App\Services\NotificacaoService;

class AvaliacaoObserver
{
    public function created(Avaliacao $avaliacao): void
    {
        // Notifica o avaliador quando a avaliação é criada
        NotificacaoService::avaliacaoAtribuida($avaliacao);
    }

    public function updated(Avaliacao $avaliacao): void
    {
        // Detecta mudança de status
        if ($avaliacao->wasChanged('status')) {
            $novo = $avaliacao->status?->value;

            // Notificação do servidor quando passa para "notificada"
            if ($novo === 'notificada') {
                NotificacaoService::avaliacaoNotificadaServidor($avaliacao);
            }

            // Aviso final quando passa para "concluida" E tem assinatura compaq
            if ($novo === 'concluida'
                && $avaliacao->assinaturas()->where('tipo_assinante', 'compaq')->exists()
                && ! $avaliacao->wasChanged('nota_total')  // evita duplicidade em saves
            ) {
                NotificacaoService::avaliacaoHomologada($avaliacao);
            }
        }
    }
}
