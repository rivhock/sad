<?php
namespace App\Services;

use App\Mail\AvaliacaoAtribuidaAvaliador;
use App\Mail\AvaliacaoNotificadaServidor;
use App\Mail\AvaliacaoHomologada;
use App\Models\Avaliacao;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificacaoService
{
    protected static function emailValido(?string $email): bool
    {
        return $email && filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    protected static function enviar(string $classe, $destinatario, $dados, string $contexto): void
    {
        if (! self::emailValido($destinatario)) {
            Log::info("Notificacao [{$contexto}]: destinatario invalido, ignorado.");
            return;
        }

        try {
            Mail::to($destinatario)->queue(new $classe($dados));
            Log::info("Notificacao [{$contexto}] enviada para {$destinatario}");
        } catch (\Throwable $e) {
            Log::error("Notificacao [{$contexto}] falhou: " . $e->getMessage());
        }
    }

    public static function avaliacaoAtribuida(Avaliacao $avaliacao): void
    {
        $email = $avaliacao->avaliador?->email;
        self::enviar(
            AvaliacaoAtribuidaAvaliador::class,
            $email,
            $avaliacao,
            "avaliacao-atribuida #{$avaliacao->id}"
        );
    }

    public static function avaliacaoNotificadaServidor(Avaliacao $avaliacao): void
    {
        $email = $avaliacao->servidor?->user?->email;
        self::enviar(
            AvaliacaoNotificadaServidor::class,
            $email,
            $avaliacao,
            "avaliacao-notificada #{$avaliacao->id}"
        );
    }

    public static function avaliacaoHomologada(Avaliacao $avaliacao): void
    {
        $email = $avaliacao->servidor?->user?->email;
        self::enviar(
            AvaliacaoHomologada::class,
            $email,
            $avaliacao,
            "avaliacao-homologada #{$avaliacao->id}"
        );
    }
}
