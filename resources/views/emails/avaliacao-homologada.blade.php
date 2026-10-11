@extends('emails.layout')

@section('titulo', 'Sua avaliação foi homologada')

@section('conteudo')
    <h1 style="font-size:20px;color:#111;margin:0 0 16px 0;">Olá, {{ $avaliacao->servidor?->user?->nome }}.</h1>

    <p style="font-size:14px;line-height:1.6;color:#374151;margin:0 0 20px 0;">
        Sua avaliação referente ao <strong>{{ $avaliacao->ciclo?->nome }}</strong> foi
        <strong style="color:#059669;">homologada pela COMPAQ</strong> e está concluída.
    </p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4;border:1px solid #86efac;border-radius:6px;margin:20px 0;">
        <tr>
            <td style="padding:16px;font-size:13px;line-height:1.8;color:#065f46;">
                <strong>Nota final:</strong> {{ $avaliacao->nota_total !== null ? number_format($avaliacao->nota_total, 2, ',', '.') : '—' }}<br>
                <strong>Data da homologação:</strong> {{ $avaliacao->updated_at?->format('d/m/Y H:i') }}
            </td>
        </tr>
    </table>

    <p style="font-size:14px;line-height:1.6;color:#374151;margin:0 0 20px 0;">
        Você pode consultar a avaliação completa, incluindo o documento assinado, pelo botão abaixo.
    </p>

    <table cellpadding="0" cellspacing="0" style="margin:30px 0;">
        <tr>
            <td style="background:#1e40af;border-radius:6px;">
                <a href="{{ $link }}"
                   style="display:inline-block;padding:14px 28px;color:#fff;text-decoration:none;font-weight:bold;font-size:14px;">
                    Consultar avaliação
                </a>
            </td>
        </tr>
    </table>

    <p style="font-size:13px;color:#6b7280;margin:20px 0 0 0;">
        Atenciosamente,<br>
        COMPAQ — Comissão Permanente de Avaliação e Qualificação
    </p>
@endsection
