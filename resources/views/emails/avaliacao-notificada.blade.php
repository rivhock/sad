@extends('emails.layout')

@section('titulo', 'Sua avaliação está disponível')

@section('conteudo')
    <h1 style="font-size:20px;color:#111;margin:0 0 16px 0;">Olá, {{ $avaliacao->servidor?->user?->nome }}.</h1>

    <p style="font-size:14px;line-height:1.6;color:#374151;margin:0 0 20px 0;">
        Sua avaliação de desempenho do <strong>{{ $avaliacao->ciclo?->nome }}</strong> foi finalizada pela chefia
        e está disponível para consulta.
    </p>

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;margin:20px 0;">
        <tr>
            <td style="padding:16px;font-size:13px;line-height:1.8;color:#374151;">
                <strong>Avaliador:</strong> {{ $avaliacao->avaliador?->nome }}<br>
                <strong>Nota total:</strong> {{ $avaliacao->nota_total !== null ? number_format($avaliacao->nota_total, 2, ',', '.') : '—' }}
                @if ($avaliacao->prazo_defesa)
                    <br><strong>Prazo para defesa:</strong> {{ $avaliacao->prazo_defesa->format('d/m/Y \à\s H:i') }}
                @endif
            </td>
        </tr>
    </table>

    <p style="font-size:14px;line-height:1.6;color:#374151;margin:0 0 20px 0;">
        Para acessar, clique no botão abaixo. Você precisará informar seu <strong>CPF</strong> para confirmar sua identidade.
    </p>

    <table cellpadding="0" cellspacing="0" style="margin:30px 0;">
        <tr>
            <td style="background:#1e40af;border-radius:6px;">
                <a href="{{ $link }}"
                   style="display:inline-block;padding:14px 28px;color:#fff;text-decoration:none;font-weight:bold;font-size:14px;">
                    Ver minha avaliação
                </a>
            </td>
        </tr>
    </table>

    <p style="font-size:13px;color:#6b7280;margin:20px 0 0 0;">
        Se discordar da avaliação, você pode apresentar defesa escrita diretamente pelo link, dentro do prazo indicado.
    </p>

    <p style="font-size:13px;color:#6b7280;margin:20px 0 0 0;">
        Atenciosamente,<br>
        COMPAQ — Comissão Permanente de Avaliação e Qualificação
    </p>
@endsection
