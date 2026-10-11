<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>@yield('titulo', 'SAD Campo Bom')</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#222;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:30px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.06);">
    <tr>
        <td style="background:#1e40af;padding:20px 30px;">
            <div style="color:#fff;font-size:20px;font-weight:bold;">SAD Campo Bom</div>
            <div style="color:#c7d2fe;font-size:12px;margin-top:4px;">Sistema de Avaliação de Desempenho</div>
        </td>
    </tr>
    <tr>
        <td style="padding:30px;">
            @yield('conteudo')
        </td>
    </tr>
    <tr>
        <td style="background:#f9fafb;padding:16px 30px;border-top:1px solid #e5e7eb;color:#6b7280;font-size:11px;text-align:center;">
            Prefeitura Municipal de Campo Bom / RS<br>
            Este é um email automático — não responda.
        </td>
    </tr>
</table>
</td></tr>
</table>
</body>
</html>
