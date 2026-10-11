<?php
namespace App\Services;

use App\Models\Assinatura;
use App\Models\Avaliacao;
use App\Models\User;

class AssinaturaService
{
    /**
     * Assinatura de usuário interno (com login).
     */
    public static function assinar(Avaliacao $avaliacao, User $user, string $tipo): Assinatura
    {
        $existente = Assinatura::where('avaliacao_id', $avaliacao->id)
            ->where('user_id', $user->id)
            ->where('tipo_assinante', $tipo)
            ->first();

        if ($existente) {
            return $existente;
        }

        $timestamp = now();
        $hash = hash('sha256', implode('|', [
            $avaliacao->id,
            $user->id,
            $tipo,
            $timestamp->toIso8601String(),
            (string) ($avaliacao->nota_total ?? ''),
            (string) $avaliacao->formulario_id,
            (string) $avaliacao->servidor_id,
        ]));

        return Assinatura::create([
            'avaliacao_id'   => $avaliacao->id,
            'user_id'        => $user->id,
            'tipo_assinante' => $tipo,
            'hash'           => $hash,
            'ip'             => request()->ip(),
            'assinado_em'    => $timestamp,
        ]);
    }

    /**
     * Assinatura de avaliador externo (sem login, com nome + CPF).
     */
    public static function assinarExterno(
        Avaliacao $avaliacao,
        string $nome,
        string $cpf,
        string $tipo = 'chefia'
    ): Assinatura {
        $existente = Assinatura::where('avaliacao_id', $avaliacao->id)
            ->where('tipo_assinante', $tipo)
            ->first();

        if ($existente) {
            return $existente;
        }

        $cpf = preg_replace('/\D/', '', $cpf);
        $timestamp = now();

        $hash = hash('sha256', implode('|', [
            $avaliacao->id,
            $nome,
            $cpf,
            $tipo,
            $timestamp->toIso8601String(),
            (string) ($avaliacao->nota_total ?? ''),
            (string) $avaliacao->formulario_id,
            (string) $avaliacao->servidor_id,
        ]));

        return Assinatura::create([
            'avaliacao_id'              => $avaliacao->id,
            'user_id'                   => null,
            'nome_avaliador_externo'    => $nome,
            'cpf_avaliador_externo'     => $cpf,
            'tipo_assinante'            => $tipo,
            'hash'                      => $hash,
            'ip'                        => request()->ip(),
            'assinado_em'               => $timestamp,
        ]);
    }
}
