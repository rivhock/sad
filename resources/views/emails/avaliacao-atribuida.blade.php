@extends('emails.layout')

@section('titulo', 'Nova avaliação atribuída')

@section('conteudo')
    <h1 style="font-size:20px;color:#111;margin:0 0 16px 0;">Olá, {{ $avaliacao->avaliador?->nome }}.</h1>

    <p style="font-size:14px;line-height:1.6;color:#374151;margin:0 0 20px 0;">
        Uma nova avaliação foi atribuída a você. Acesse o painel do avaliador para preencher as notas.
    </p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;margin:20px 0;">
        <tr>
            <td style="padding:16px;font-size:13px;line-height:1.8;color:#374151;">
                <strong>Servidor:</strong> {{ $avaliacao->servidor?->user?->nome }} (mat. {{ $avaliacao->servidor?->user?->matricula }})<br>
                <strong>Ciclo:</strong> {{ $avaliacao->ciclo?->nome }}<br>
                <strong>Formulário:</strong> {{ $avaliacao->formulario?->titulo }}<br>
                <strong>Tipo:</strong> {{ $avaliacao->tipo === 'estagio_probatorio' ? 'Estágio Probatório' : 'Avaliação de Desempenho' }}
            </td>
        </tr>
    </table>

    <table cellpadding="0" cellspacing="0" style="margin:30px 0;">
        <tr>
            <td style="background:#1e40af;border-radius:6px;">
                <a href="{{ url('/avaliador/avaliacaos/' . $avaliacao->id . '/preencher') }}"
                   style="display:inline-block;padding:14px 28px;color:#fff;text-decoration:none;font-weight:bold;font-size:14px;">
                    Preencher Avaliação
                </a>
            </td>
        </tr>
    </table>

    <p style="font-size:13px;color:#6b7280;margin:20px 0 0 0;">
        Ou acesse o painel do avaliador em <a href="{{ url('/avaliador') }}" style="color:#1e40af;">{{ url('/avaliador') }}</a>.
    </p>
@endsection
