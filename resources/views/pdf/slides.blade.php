<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
@page {
    size: 297mm 210mm;
    margin: 0;
    padding: 0;
}
* {
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
}
html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
}
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 10px;
    color: #1a1a1a;
}

/* ============ SLIDE BASE ============ */
.slide {
    width: 100%;
    height: 200mm;
    padding: 12mm 15mm 10mm 15mm;
    box-sizing: border-box;
    page-break-after: always;
    page-break-inside: avoid;
    position: relative;
    background: #fff;
}
.slide:last-child {
    page-break-after: avoid;
    page-break-inside: auto;
}

.slide-header {
    border-bottom: 3px solid #1e40af;
    padding-bottom: 6px;
    margin-bottom: 12px;
}
.slide-header .brasao {
    height: 22px;
    vertical-align: middle;
    margin-right: 8px;
}
.slide-header .titulo {
    display: inline-block;
    vertical-align: middle;
    color: #1e40af;
    font-size: 16px;
    font-weight: bold;
    letter-spacing: 0.5px;
}
.slide-header .subtitulo {
    display: inline-block;
    vertical-align: middle;
    color: #64748b;
    font-size: 9px;
    float: right;
    padding-top: 6px;
}

.slide-numero {
    position: absolute;
    bottom: 6mm;
    right: 15mm;
    font-size: 8px;
    color: #94a3b8;
    font-weight: bold;
}
.slide-rodape {
    position: absolute;
    bottom: 6mm;
    left: 15mm;
    font-size: 8px;
    color: #94a3b8;
}

/* ============ CAPA ============ */
.capa {
    background: #0a0e1a;
    color: #fff;
    width: 100%;
    height: 200mm;
    padding: 25mm 30mm;
    box-sizing: border-box;
    page-break-after: always;
    page-break-inside: avoid;
    text-align: center;
    position: relative;
}
.capa img {
    height: 110px;
}
.capa h1 {
    font-size: 62px;
    margin: 25px 0 8px 0;
    letter-spacing: 8px;
    color: #fff;
}
.capa h2 {
    font-size: 15px;
    font-weight: normal;
    color: #60a5fa;
    letter-spacing: 3px;
    margin: 0;
}
.capa .divisor {
    width: 100px;
    height: 3px;
    background: #3b82f6;
    margin: 25px auto;
}
.capa .subtitulo {
    font-size: 12px;
    color: #94a3b8;
    line-height: 1.8;
}
.capa .rodape-capa {
    position: absolute;
    bottom: 20mm;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 9px;
    color: #64748b;
    border-top: 1px solid #1e293b;
    padding-top: 15px;
    margin: 0 30mm;
}

/* ============ ÍNDICE ============ */
.indice-grid {
    width: 100%;
    border-collapse: separate;
    border-spacing: 10px;
    margin-top: 15px;
}
.indice-grid td {
    width: 50%;
    background: #f8fafc;
    border-left: 4px solid #1e40af;
    padding: 14px 18px;
    border-radius: 4px;
    vertical-align: top;
}
.indice-grid .num {
    display: inline-block;
    width: 26px;
    height: 26px;
    background: #1e40af;
    color: #fff;
    border-radius: 50%;
    text-align: center;
    line-height: 26px;
    font-weight: bold;
    font-size: 11px;
    margin-right: 8px;
}
.indice-grid .titulo {
    display: inline-block;
    vertical-align: middle;
    font-size: 12px;
    color: #1e293b;
    font-weight: bold;
}
.indice-grid .desc {
    font-size: 9.5px;
    color: #64748b;
    margin-top: 6px;
    line-height: 1.5;
}

/* ============ MOCKUP DE TELA ============ */
.mockup {
    background: #0f172a;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #1e293b;
}
.mockup-topo {
    background: #1e293b;
    padding: 7px 12px;
    border-bottom: 1px solid #334155;
}
.mockup-topo .bolinha {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 4px;
}
.mockup-topo .url {
    display: inline-block;
    background: #0f172a;
    color: #94a3b8;
    padding: 3px 12px;
    border-radius: 4px;
    font-size: 8px;
    font-family: monospace;
    margin-left: 12px;
    vertical-align: middle;
}
.mockup-conteudo {
    padding: 14px 16px;
    color: #e2e8f0;
}

.sidebar {
    display: inline-block;
    width: 21%;
    vertical-align: top;
    background: #1e293b;
    padding: 10px 8px;
    border-radius: 6px;
    margin-right: 2%;
    min-height: 130px;
}
.sidebar .item {
    padding: 5px 8px;
    color: #cbd5e1;
    border-radius: 4px;
    margin-bottom: 3px;
    font-size: 8.5px;
}
.sidebar .item.ativo {
    background: #1e40af;
    color: #fff;
    font-weight: bold;
}
.sidebar .item.ativo.amber {
    background: #d97706;
}
.main-area {
    display: inline-block;
    width: 76%;
    vertical-align: top;
}
.main-area h4 {
    color: #fff;
    font-size: 13px;
    margin: 0 0 10px 0;
}

.card-stat {
    display: inline-block;
    width: 31%;
    background: #1e293b;
    border-radius: 6px;
    padding: 10px;
    margin-right: 2%;
    margin-bottom: 8px;
    vertical-align: top;
}
.card-stat .lbl { color: #94a3b8; font-size: 7px; text-transform: uppercase; }
.card-stat .val { color: #fff; font-size: 20px; font-weight: bold; line-height: 1.2; }
.card-stat .desc { color: #64748b; font-size: 7px; margin-top: 2px; }

/* Tabela dentro do mockup */
.mockup table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 6px;
}
.mockup table th {
    background: #1e40af;
    color: #fff;
    padding: 6px 8px;
    text-align: left;
    font-size: 8px;
}
.mockup table td {
    padding: 6px 8px;
    border-bottom: 1px solid #334155;
    font-size: 8.5px;
    color: #e2e8f0;
}

/* Badges */
.badge {
    display: inline-block;
    padding: 2px 7px;
    border-radius: 8px;
    font-size: 7px;
    font-weight: bold;
}
.b-ok { background: #d1fae5; color: #065f46; }
.b-info { background: #dbeafe; color: #1e40af; }
.b-warn { background: #fef3c7; color: #92400e; }
.b-danger { background: #fee2e2; color: #991b1b; }
.b-gray { background: #e5e7eb; color: #4b5563; }
.b-purple { background: #ede9fe; color: #6b21a8; }

/* Layout de 2 colunas */
.col-2 {
    width: 100%;
    border-collapse: separate;
    border-spacing: 8px 0;
}
.col-2 td {
    vertical-align: top;
}

/* Caixas informativas */
.caixa {
    padding: 10px 14px;
    border-radius: 6px;
    margin: 8px 0;
    font-size: 9.5px;
    line-height: 1.5;
}
.caixa-info { background: #eff6ff; border-left: 4px solid #3b82f6; color: #1e3a8a; }
.caixa-ok { background: #f0fdf4; border-left: 4px solid #10b981; color: #065f46; }
.caixa-warn { background: #fffbeb; border-left: 4px solid #f59e0b; color: #78350f; }
.caixa-danger { background: #fef2f2; border-left: 4px solid #dc2626; color: #7f1d1d; }

/* Títulos de seção */
.secao-titulo {
    color: #1e40af;
    font-size: 13px;
    font-weight: bold;
    margin: 10px 0 6px 0;
    border-left: 3px solid #1e40af;
    padding-left: 10px;
}
.subtitulo-slide {
    color: #64748b;
    font-size: 10px;
    margin-bottom: 10px;
}
</style>
</head>
<body>

<!-- ============ SLIDE 1 — CAPA ============ -->
<div class="capa">
    <img src="file://{{ public_path('brasao.png') }}" alt="Brasão">
    <h1>SAD</h1>
    <h2>SISTEMA DE AVALIAÇÃO DE DESEMPENHO</h2>
    <div class="divisor"></div>
    <div class="subtitulo">
        Prefeitura Municipal de Campo Bom / RS<br>
        Comissão Permanente de Avaliação e Qualificação — COMPAQ
    </div>
    <div class="rodape-capa">
        <strong style="color:#60a5fa;">Guia de Uso — Todos os Perfis</strong><br>
        Documento gerado em {{ now()->format('d/m/Y') }}
    </div>
</div>

<!-- ============ SLIDE 2 — ÍNDICE ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">Índice</span>
        <span class="subtitulo">Visão geral da apresentação</span>
    </div>

    <table class="indice-grid">
        <tr>
            <td>
                <span class="num">1</span><span class="titulo">Login Unificado</span>
                <div class="desc">Como acessar. Sistema detecta o perfil automaticamente.</div>
            </td>
            <td>
                <span class="num">2</span><span class="titulo">Painel COMPAQ</span>
                <div class="desc">Dashboard, criação de avaliações, análise de defesa e parecer.</div>
            </td>
        </tr>
        <tr>
            <td>
                <span class="num">3</span><span class="titulo">Painel do Avaliador</span>
                <div class="desc">Chefia preenche notas, assina e notifica o servidor.</div>
            </td>
            <td>
                <span class="num">4</span><span class="titulo">Link do Servidor</span>
                <div class="desc">Sem senha. Acesso por CPF, ciência, cursos e defesa.</div>
            </td>
        </tr>
        <tr>
            <td>
                <span class="num">5</span><span class="titulo">Painel do Administrador</span>
                <div class="desc">Usuários, importação CSV, log de auditoria e backup.</div>
            </td>
            <td>
                <span class="num">6</span><span class="titulo">Fluxo Completo</span>
                <div class="desc">Da criação da avaliação até o PDF com assinaturas.</div>
            </td>
        </tr>
    </table>

    <div class="caixa caixa-info" style="margin-top: 20px;">
        <strong>💡 Como usar este guia:</strong> cada slide mostra uma tela real do sistema.
        Os mockups reproduzem fielmente o visual do SAD para facilitar o treinamento.
    </div>

    <div class="slide-numero">2</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 3 — LOGIN ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">1. Login Unificado</span>
        <span class="subtitulo">Acesso único com detecção automática de perfil</span>
    </div>

    <table class="col-2">
        <tr>
            <td style="width: 45%;">
                <div class="mockup">
                    <div class="mockup-topo">
                        <span class="bolinha" style="background:#ef4444"></span>
                        <span class="bolinha" style="background:#f59e0b"></span>
                        <span class="bolinha" style="background:#10b981"></span>
                        <span class="url">sad.campobom.rs.gov.br</span>
                    </div>
                    <div class="mockup-conteudo" style="padding: 30px 25px;">
                        <div style="text-align: center; margin-bottom: 20px;">
                            <img src="file://{{ public_path('brasao.png') }}" style="height: 55px;">
                            <div style="color:#fff; font-size:16px; font-weight:bold; margin-top:6px;">SAD Campo Bom</div>
                            <div style="color:#94a3b8; font-size:9px;">Sistema de Avaliação de Desempenho</div>
                        </div>
                        <div style="color:#cbd5e1; font-size:9px; margin-bottom:4px;">E-mail</div>
                        <div style="background:#1e293b; border:1px solid #334155; border-radius:4px; padding:8px 10px; color:#94a3b8; font-size:9px; margin-bottom:12px;">seu.email@campobom.rs.gov.br</div>
                        <div style="color:#cbd5e1; font-size:9px; margin-bottom:4px;">Senha</div>
                        <div style="background:#1e293b; border:1px solid #334155; border-radius:4px; padding:8px 10px; color:#94a3b8; font-size:9px; margin-bottom:16px;">••••••••</div>
                        <div style="background:#1e40af; color:#fff; text-align:center; padding:10px; border-radius:4px; font-weight:bold; font-size:11px;">Entrar</div>
                    </div>
                </div>
            </td>
            <td style="width: 55%; padding-left: 15px;">
                <div class="secao-titulo">Como funciona</div>
                <div style="font-size: 10.5px; line-height: 1.7; color: #334155;">
                    Todos os perfis entram pelo <strong>mesmo endereço</strong>:
                    <code style="background:#f1f5f9; padding:2px 6px; border-radius:3px; color:#1e40af; font-size:10px;">https://sad.campobom.rs.gov.br</code>
                </div>

                <div class="secao-titulo">Detecção automática de perfil</div>
                <table style="width:100%; font-size:10px; border-collapse:collapse;">
                    <tr>
                        <td style="padding:6px 10px; background:#f1f5f9; border-radius:4px 0 0 4px; width:35%;"><strong>Administrador / RH</strong></td>
                        <td style="padding:6px 10px; background:#f8fafc;">→ Painel <code>/admin</code></td>
                    </tr>
                    <tr><td colspan="2" style="height:4px;"></td></tr>
                    <tr>
                        <td style="padding:6px 10px; background:#f1f5f9; border-radius:4px 0 0 4px;"><strong>COMPAQ</strong></td>
                        <td style="padding:6px 10px; background:#f8fafc;">→ Painel <code>/compaq</code></td>
                    </tr>
                    <tr><td colspan="2" style="height:4px;"></td></tr>
                    <tr>
                        <td style="padding:6px 10px; background:#f1f5f9; border-radius:4px 0 0 4px;"><strong>Avaliador (chefia)</strong></td>
                        <td style="padding:6px 10px; background:#f8fafc;">→ Painel <code>/avaliador</code></td>
                    </tr>
                </table>

                <div class="caixa caixa-info" style="margin-top: 15px;">
                    <strong>🔐 Segurança:</strong> após 5 tentativas erradas, o login é
                    bloqueado por 1 minuto (proteção contra força bruta).
                </div>
            </td>
        </tr>
    </table>

    <div class="slide-numero">3</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 4 — FLUXO ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">2. Fluxo Completo da Avaliação</span>
        <span class="subtitulo">Da criação até a homologação</span>
    </div>

    <div style="margin-top: 10px;">
        @php
            $fluxo = [
                ['c' => '#1e40af', 'n' => '1', 't' => 'COMPAQ cria a avaliação', 'd' => 'Individual ou em massa, por secretaria, categoria ou período.'],
                ['c' => '#d97706', 'n' => '2', 't' => 'Chefia preenche e assina', 'd' => 'Recebe e-mail com link. Preenche 10 notas e assina digitalmente.'],
                ['c' => '#7c3aed', 'n' => '3', 't' => 'Sistema notifica o servidor', 'd' => 'Envia e-mail com link pessoal para tomar ciência.'],
                ['c' => '#0ea5e9', 'n' => '4', 't' => 'Servidor assina ciência', 'd' => 'Acessa por CPF, vê a avaliação e assina.'],
                ['c' => '#dc2626', 'n' => '5', 't' => 'Defesa (opcional)', 'd' => 'Se discordar, protocola defesa escrita com anexo em PDF.'],
                ['c' => '#ea580c', 'n' => '6', 't' => 'COMPAQ analisa a defesa', 'd' => 'Emite parecer: deferida, parcial, indeferida ou PAD.'],
                ['c' => '#059669', 'n' => '7', 't' => 'COMPAQ homologa', 'd' => 'Assina digitalmente. Ciclo concluído com todas as assinaturas.'],
            ];
        @endphp

        @foreach ($fluxo as $f)
            <table style="width:100%; margin-bottom:6px;">
                <tr>
                    <td style="width: 40px; vertical-align: middle;">
                        <div style="width:30px; height:30px; background:{{ $f['c'] }}; color:#fff; border-radius:50%; text-align:center; line-height:30px; font-weight:bold; font-size:13px;">{{ $f['n'] }}</div>
                    </td>
                    <td style="background:#f8fafc; border-left:4px solid {{ $f['c'] }}; padding:8px 14px; border-radius:0 4px 4px 0;">
                        <div style="font-weight:bold; font-size:10.5px; color:#1e293b;">{{ $f['t'] }}</div>
                        <div style="font-size:9px; color:#64748b; margin-top:2px;">{{ $f['d'] }}</div>
                    </td>
                </tr>
            </table>
        @endforeach
    </div>

    <div class="caixa caixa-ok" style="margin-top:10px;">
        <strong>🔒 Auditoria completa:</strong> cada etapa registra usuário, data/hora, IP e hash de integridade — validade jurídica comprovável.
    </div>

    <div class="slide-numero">4</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 5 — DASHBOARD COMPAQ ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">3. Painel COMPAQ — Dashboard</span>
        <span class="subtitulo">Indicadores em tempo real</span>
    </div>

    <div class="mockup">
        <div class="mockup-topo">
            <span class="bolinha" style="background:#ef4444"></span>
            <span class="bolinha" style="background:#f59e0b"></span>
            <span class="bolinha" style="background:#10b981"></span>
            <span class="url">sad.campobom.rs.gov.br/compaq</span>
        </div>
        <div class="mockup-conteudo">
            <div class="sidebar">
                <div class="item ativo">🏠 Painel de Controle</div>
                <div class="item">📋 Avaliações</div>
                <div class="item">✨ Gerar em Massa</div>
                <div class="item">🎓 Qualificações</div>
                <div class="item">🔄 Ciclos</div>
                <div class="item">📝 Formulários</div>
                <div class="item">👥 Servidores</div>
            </div>
            <div class="main-area">
                <h4>Painel de Controle</h4>
                <div class="card-stat">
                    <div class="lbl">Avaliações ativas</div>
                    <div class="val">487</div>
                    <div class="desc">Ciclo 2026</div>
                </div>
                <div class="card-stat">
                    <div class="lbl">Pendentes</div>
                    <div class="val">142</div>
                    <div class="desc">Aguardando preenchimento</div>
                </div>
                <div class="card-stat">
                    <div class="lbl">Concluídas</div>
                    <div class="val">315</div>
                    <div class="desc">64% do total</div>
                </div>
                <div class="card-stat">
                    <div class="lbl">Em defesa</div>
                    <div class="val">12</div>
                    <div class="desc">Aguardando análise</div>
                </div>
                <div class="card-stat">
                    <div class="lbl">Cursos em análise</div>
                    <div class="val">28</div>
                    <div class="desc">Certificados recebidos</div>
                </div>
                <div class="card-stat" style="background:#1e40af;">
                    <div class="lbl" style="color:#bfdbfe;">Aguardando COMPAQ</div>
                    <div class="val">8</div>
                    <div class="desc" style="color:#93c5fd;">Clique para homologar</div>
                </div>
            </div>
        </div>
    </div>

    <table class="col-2" style="margin-top:12px;">
        <tr>
            <td style="width:50%;">
                <div class="secao-titulo" style="font-size:11px;">📊 Gráficos em tempo real</div>
                <div style="font-size:9.5px; color:#334155; line-height:1.6;">
                    • Distribuição de avaliações por status (rosca)<br>
                    • Avaliações por secretaria (barras empilhadas)<br>
                    • Últimas avaliações movimentadas
                </div>
            </td>
            <td style="width:50%; padding-left:12px;">
                <div class="secao-titulo" style="font-size:11px;">🎯 Ação rápida</div>
                <div style="font-size:9.5px; color:#334155; line-height:1.6;">
                    O card azul <strong>"Aguardando COMPAQ"</strong> é clicável.
                    Leva direto para as avaliações que precisam de homologação.
                </div>
            </td>
        </tr>
    </table>

    <div class="slide-numero">5</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 6 — GERAR EM MASSA ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">4. Geração em Massa</span>
        <span class="subtitulo">Criação de centenas de avaliações em minutos</span>
    </div>

    <table class="col-2">
        <tr>
            <td style="width: 55%;">
                <div class="mockup">
                    <div class="mockup-topo">
                        <span class="bolinha" style="background:#ef4444"></span>
                        <span class="bolinha" style="background:#f59e0b"></span>
                        <span class="bolinha" style="background:#10b981"></span>
                        <span class="url">/compaq/gerar-avaliacoes-massa</span>
                    </div>
                    <div class="mockup-conteudo">
                        <h4 style="font-size:11px;">1. Ciclo e Tipo</h4>
                        <table style="margin-bottom:10px;">
                            <tr>
                                <td style="background:#1e293b; border-radius:4px; padding:6px; font-size:8px; width:50%;">Ciclo 2026</td>
                                <td style="width:10px;"></td>
                                <td style="background:#1e293b; border-radius:4px; padding:6px; font-size:8px;">Avaliação de Desempenho</td>
                            </tr>
                        </table>

                        <h4 style="font-size:11px;">2. Filtros de Servidores</h4>
                        <table style="margin-bottom:10px;">
                            <tr>
                                <td style="background:#1e293b; border-radius:4px; padding:6px; font-size:8px; width:33%;">Secretaria</td>
                                <td style="width:6px;"></td>
                                <td style="background:#1e293b; border-radius:4px; padding:6px; font-size:8px; width:33%;">Categoria</td>
                                <td style="width:6px;"></td>
                                <td style="background:#1e293b; border-radius:4px; padding:6px; font-size:8px;">Admissão</td>
                            </tr>
                        </table>

                        <h4 style="font-size:11px;">3. Opções</h4>
                        <div style="background:#1e293b; border-radius:4px; padding:8px; font-size:8px; color:#cbd5e1;">
                            ☑ Gerar apenas para servidores com chefia vinculada
                        </div>
                    </div>
                </div>
            </td>
            <td style="width: 45%; padding-left:12px;">
                <div class="secao-titulo">Como usar</div>
                <div style="font-size:10px; color:#334155; line-height:1.6;">
                    <strong>1.</strong> Escolha o ciclo e o tipo de avaliação<br>
                    <strong>2.</strong> Filtre por secretaria, categoria ou período<br>
                    <strong>3.</strong> Clique em <strong>🔎 Simular</strong> para ver quantas seriam criadas<br>
                    <strong>4.</strong> Confirme com <strong>✨ Gerar Avaliações</strong>
                </div>

                <div class="secao-titulo" style="margin-top:12px;">O que a simulação mostra</div>
                <table style="width:100%; font-size:9px; border-collapse:collapse;">
                    <tr style="background:#eff6ff;">
                        <td style="padding:5px 8px; width:60%;">Servidores no filtro</td>
                        <td style="padding:5px 8px; font-weight:bold;">487</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 8px;">Seriam criadas</td>
                        <td style="padding:5px 8px; font-weight:bold; color:#059669;">342</td>
                    </tr>
                    <tr style="background:#eff6ff;">
                        <td style="padding:5px 8px;">Já tinham avaliação</td>
                        <td style="padding:5px 8px; font-weight:bold; color:#ca8a04;">133</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 8px;">Sem chefia vinculada</td>
                        <td style="padding:5px 8px; font-weight:bold; color:#dc2626;">12</td>
                    </tr>
                </table>

                <div class="caixa caixa-ok" style="margin-top:10px; font-size:9px;">
                    <strong>✓ Seguro:</strong> a simulação não cria nada. Só depois de confirmar é que as avaliações são geradas.
                </div>
            </td>
        </tr>
    </table>

    <div class="slide-numero">6</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 7 — ANÁLISE DE DEFESA ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">5. Análise de Defesa pela COMPAQ</span>
        <span class="subtitulo">Parecer estruturado com 4 decisões possíveis</span>
    </div>

    <div class="mockup">
        <div class="mockup-topo">
            <span class="bolinha" style="background:#ef4444"></span>
            <span class="bolinha" style="background:#f59e0b"></span>
            <span class="bolinha" style="background:#10b981"></span>
            <span class="url">Modal: Analisar Defesa</span>
        </div>
        <div class="mockup-conteudo" style="padding: 16px;">
            <div style="background:#4c1d95; border-radius:6px; padding:12px; margin-bottom:12px;">
                <div style="color:#ddd6fe; font-size:8px; text-transform:uppercase; font-weight:bold;">⚖️ Defesa apresentada pelo servidor</div>
                <div style="color:#c4b5fd; font-size:8px; margin:4px 0 8px 0;">Protocolada em 03/10/2026 às 18:18</div>
                <div style="background:#f5f3ff; color:#4c1d95; padding:10px; border-radius:4px; font-size:9px; line-height:1.5;">
                    "Não concordo com a nota do item 5. Sempre cumpri os prazos e tenho comprovantes anexos."
                </div>
                <div style="margin-top:8px; color:#c4b5fd; font-size:8px;">📎 Ver anexo</div>
            </div>

            <div style="color:#94a3b8; font-size:8px; text-transform:uppercase; margin-bottom:4px;">Nota atual da avaliação</div>
            <div style="background:#1e293b; border-radius:4px; padding:8px 12px; margin-bottom:12px;">
                <span style="color:#94a3b8; font-size:9px;">50,00</span>
                <span style="float:right; color:#22d3ee; font-weight:bold; font-size:14px;">50,00</span>
            </div>

            <div style="color:#94a3b8; font-size:8px; text-transform:uppercase; margin-bottom:4px;">Decisão</div>
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:25%; background:#065f46; color:#fff; padding:8px; border-radius:4px; text-align:center; font-size:8px; font-weight:bold;">Deferida</td>
                    <td style="width:5px;"></td>
                    <td style="width:25%; background:#1e40af; color:#fff; padding:8px; border-radius:4px; text-align:center; font-size:8px;">Parcial</td>
                    <td style="width:5px;"></td>
                    <td style="width:25%; background:#92400e; color:#fff; padding:8px; border-radius:4px; text-align:center; font-size:8px;">Indeferida</td>
                    <td style="width:5px;"></td>
                    <td style="width:25%; background:#991b1b; color:#fff; padding:8px; border-radius:4px; text-align:center; font-size:8px;">PAD</td>
                </tr>
            </table>
        </div>
    </div>

    <table class="col-2" style="margin-top:10px;">
        <tr>
            <td style="width:50%;">
                <div class="secao-titulo" style="font-size:11px;">O que a COMPAQ pode fazer</div>
                <div style="font-size:9.5px; color:#334155; line-height:1.6;">
                    • <strong>Deferida:</strong> aceita a defesa, ajusta a nota<br>
                    • <strong>Parcial:</strong> aceita em parte, ajusta parcialmente<br>
                    • <strong>Indeferida:</strong> mantém a nota original<br>
                    • <strong>Encaminhar PAD:</strong> processo administrativo
                </div>
            </td>
            <td style="width:50%; padding-left:12px;">
                <div class="secao-titulo" style="font-size:11px;">Após o parecer</div>
                <div style="font-size:9.5px; color:#334155; line-height:1.6;">
                    A avaliação volta para o servidor tomar ciência da versão final.
                    O parecer fica registrado com texto, decisão, autor e data/hora.
                </div>
            </td>
        </tr>
    </table>

    <div class="slide-numero">7</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 8 — QUALIFICAÇÕES ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">6. Qualificações — Progressão por Cursos</span>
        <span class="subtitulo">Servidor anexa certificados, COMPAQ analisa</span>
    </div>

    <table class="col-2">
        <tr>
            <td style="width: 55%;">
                <div class="mockup">
                    <div class="mockup-topo">
                        <span class="bolinha" style="background:#ef4444"></span>
                        <span class="bolinha" style="background:#f59e0b"></span>
                        <span class="bolinha" style="background:#10b981"></span>
                        <span class="url">/compaq/qualificacoes</span>
                    </div>
                    <div class="mockup-conteudo">
                        <h4 style="font-size:11px;">Qualificações — Em análise</h4>
                        <table>
                            <tr>
                                <th>Servidor</th>
                                <th>Título</th>
                                <th>Tipo</th>
                                <th>Carga</th>
                                <th>Status</th>
                            </tr>
                            <tr>
                                <td>Vinicius C.</td>
                                <td>Excel Avançado</td>
                                <td><span class="badge b-info">Curso</span></td>
                                <td>20h</td>
                                <td><span class="badge b-warn">Em análise</span></td>
                            </tr>
                            <tr>
                                <td>Rivelino H.</td>
                                <td>LGPD na prática</td>
                                <td><span class="badge b-purple">Palestra</span></td>
                                <td>8h</td>
                                <td><span class="badge b-warn">Em análise</span></td>
                            </tr>
                            <tr>
                                <td>Maria S.</td>
                                <td>Atendimento SUS</td>
                                <td><span class="badge b-info">Curso</span></td>
                                <td>40h</td>
                                <td><span class="badge b-ok">Aprovado</span></td>
                            </tr>
                        </table>
                        <div style="margin-top:10px; text-align:right;">
                            <span style="background:#059669; color:#fff; padding:5px 12px; border-radius:4px; font-size:8px; font-weight:bold;">✓ Aprovar</span>
                            <span style="background:#dc2626; color:#fff; padding:5px 12px; border-radius:4px; font-size:8px; font-weight:bold; margin-left:6px;">✗ Rejeitar</span>
                        </div>
                    </div>
                </div>
            </td>
            <td style="width: 45%; padding-left:12px;">
                <div class="secao-titulo">Tipos aceitos</div>
                <table style="width:100%; font-size:10px; border-collapse:collapse; margin-bottom:10px;">
                    <tr>
                        <td style="padding:8px 12px; background:#eff6ff; border-left:3px solid #3b82f6; border-radius:4px;">
                            <strong>📘 Curso</strong>
                            <div style="font-size:9px; color:#64748b; margin-top:2px;">Qualquer curso de capacitação</div>
                        </td>
                    </tr>
                    <tr><td style="height:6px;"></td></tr>
                    <tr>
                        <td style="padding:8px 12px; background:#f5f3ff; border-left:3px solid #8b5cf6; border-radius:4px;">
                            <strong>🎤 Palestra</strong>
                            <div style="font-size:9px; color:#64748b; margin-top:2px;">Participação em palestras e eventos</div>
                        </td>
                    </tr>
                </table>

                <div class="caixa caixa-warn" style="font-size:9px;">
                    <strong>⚠️ Regra:</strong> carga horária mínima de <strong>4 horas</strong>. Arquivo em PDF, máximo 10 MB.
                </div>

                <div class="caixa caixa-info" style="margin-top:8px; font-size:9px;">
                    <strong>ℹ️ Fluxo:</strong> servidor envia → COMPAQ analisa → aprova ou rejeita → horas aprovadas são acumuladas.
                </div>
            </td>
        </tr>
    </table>

    <div class="slide-numero">8</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 9 — PAINEL AVALIADOR ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">7. Painel do Avaliador (Chefia)</span>
        <span class="subtitulo">Acesso restrito às avaliações atribuídas a ele</span>
    </div>

    <div class="mockup">
        <div class="mockup-topo">
            <span class="bolinha" style="background:#ef4444"></span>
            <span class="bolinha" style="background:#f59e0b"></span>
            <span class="bolinha" style="background:#10b981"></span>
            <span class="url">sad.campobom.rs.gov.br/avaliador</span>
        </div>
        <div class="mockup-conteudo">
            <div class="sidebar">
                <div class="item ativo amber">🏠 Painel de Controle</div>
                <div style="color:#94a3b8; font-size:7.5px; padding:4px 8px; text-transform:uppercase; margin-top:6px;">Minhas Avaliações</div>
                <div class="item">📋 Avaliações para mim</div>
            </div>
            <div class="main-area">
                <h4>Avaliações atribuídas a mim</h4>
                <table>
                    <tr>
                        <th>Servidor</th>
                        <th>Matrícula</th>
                        <th>Ciclo</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                    <tr>
                        <td>Vinicius Cardoso</td>
                        <td>0232</td>
                        <td><span class="badge b-warn">Ciclo 2026</span></td>
                        <td><span class="badge b-warn">Pendente</span></td>
                        <td>✏️ Preencher</td>
                    </tr>
                    <tr>
                        <td>Rivelino da Silva</td>
                        <td>11438</td>
                        <td><span class="badge b-warn">Ciclo 2026</span></td>
                        <td><span class="badge b-info">Notificada</span></td>
                        <td>👁️ Ver · 📄 PDF</td>
                    </tr>
                    <tr>
                        <td>Maria Santos</td>
                        <td>0233</td>
                        <td><span class="badge b-warn">Ciclo 2026</span></td>
                        <td><span class="badge b-ok">Concluída</span></td>
                        <td>👁️ Ver · 📄 PDF · 🔗 Link</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <table class="col-2" style="margin-top:10px;">
        <tr>
            <td style="width:50%;">
                <div class="secao-titulo" style="font-size:11px;">Isolamento de dados</div>
                <div style="font-size:9.5px; color:#334155; line-height:1.6;">
                    Cada chefia vê <strong>apenas</strong> as avaliações em que ela é a avaliadora.
                    Inclusive o Administrador não vê as avaliações dos outros no painel do avaliador.
                </div>
            </td>
            <td style="width:50%; padding-left:12px;">
                <div class="secao-titulo" style="font-size:11px;">Notificação automática</div>
                <div style="font-size:9.5px; color:#334155; line-height:1.6;">
                    Quando a COMPAQ cria uma avaliação, a chefia recebe
                    <strong>e-mail automático</strong> com link direto para o preenchimento.
                </div>
            </td>
        </tr>
    </table>

    <div class="slide-numero">9</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 10 — PREENCHIMENTO ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">8. Preenchimento da Avaliação</span>
        <span class="subtitulo">10 itens, cálculo automático e assinatura em um clique</span>
    </div>

    <div class="mockup">
        <div class="mockup-topo">
            <span class="bolinha" style="background:#ef4444"></span>
            <span class="bolinha" style="background:#f59e0b"></span>
            <span class="bolinha" style="background:#10b981"></span>
            <span class="url">/avaliador/avaliacaos/8/preencher</span>
        </div>
        <div class="mockup-conteudo">
            <table style="width:100%; margin-bottom:8px;">
                <tr>
                    <td style="background:#1e293b; border-radius:4px; padding:6px; font-size:8px; color:#cbd5e1;">Servidor: <strong style="color:#fff;">Vinicius Cardoso</strong></td>
                    <td style="width:6px;"></td>
                    <td style="background:#1e293b; border-radius:4px; padding:6px; font-size:8px; color:#cbd5e1;">Ciclo: <strong style="color:#fff;">2026</strong></td>
                    <td style="width:6px;"></td>
                    <td style="background:#1e293b; border-radius:4px; padding:6px; font-size:8px; color:#cbd5e1;">Formulário: <strong style="color:#fff;">Quadro Geral</strong></td>
                </tr>
            </table>

            <table>
                <tr style="background:#1e293b;">
                    <td style="width:30px; color:#94a3b8; text-align:center;">1</td>
                    <td style="color:#cbd5e1; font-size:8px;">Assiduidade, pontualidade e registro funcional</td>
                    <td style="width:60px; text-align:center;"><div style="background:#0f172a; border-radius:4px; padding:5px; color:#22d3ee; font-weight:bold; text-align:center;">9,0</div></td>
                </tr>
                <tr>
                    <td style="text-align:center; color:#94a3b8;">2</td>
                    <td style="color:#cbd5e1; font-size:8px;">Relacionamento interpessoal e comunicação</td>
                    <td style="text-align:center;"><div style="background:#0f172a; border-radius:4px; padding:5px; color:#22d3ee; font-weight:bold; text-align:center;">8,5</div></td>
                </tr>
                <tr style="background:#1e293b;">
                    <td style="text-align:center; color:#94a3b8;">3</td>
                    <td style="color:#cbd5e1; font-size:8px;">Uso adequado de materiais e zelo ao patrimônio</td>
                    <td style="text-align:center;"><div style="background:#0f172a; border-radius:4px; padding:5px; color:#22d3ee; font-weight:bold; text-align:center;">9,0</div></td>
                </tr>
                <tr>
                    <td style="text-align:center; color:#94a3b8;">...</td>
                    <td style="color:#64748b; font-size:8px; font-style:italic;">(demais itens)</td>
                    <td></td>
                </tr>
            </table>

            <div style="background:#1e40af; border-radius:6px; padding:10px 15px; margin-top:10px;">
                <span style="color:#bfdbfe; font-size:10px;">NOTA TOTAL:</span>
                <span style="float:right; color:#fff; font-size:18px; font-weight:bold;">87,50</span>
            </div>

            <div style="margin-top:10px; text-align:right;">
                <span style="background:#475569; color:#fff; padding:6px 14px; border-radius:4px; font-size:8px;">💾 Salvar</span>
                <span style="background:#059669; color:#fff; padding:6px 14px; border-radius:4px; font-size:8px; font-weight:bold; margin-left:6px;">✍️ Assinar e Enviar</span>
            </div>
        </div>
    </div>

    <table class="col-2" style="margin-top:10px;">
        <tr>
            <td style="width:33%;">
                <div class="caixa caixa-info" style="font-size:9px;">
                    <strong>💾 Salvar</strong><br>
                    Só grava as notas. Permite voltar depois e continuar.
                </div>
            </td>
            <td style="width:33%;">
                <div class="caixa caixa-ok" style="font-size:9px;">
                    <strong>✍️ Assinar e Enviar</strong><br>
                    Salva + assina (hash + IP) + notifica o servidor por e-mail.
                </div>
            </td>
            <td style="width:34%;">
                <div class="caixa caixa-warn" style="font-size:9px;">
                    <strong>⚠️ Regra</strong><br>
                    O botão só libera quando todas as 10 notas estão preenchidas.
                </div>
            </td>
        </tr>
    </table>

    <div class="slide-numero">10</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 11 — LINK DO SERVIDOR ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">9. Link do Servidor Avaliado</span>
        <span class="subtitulo">Sem senha. Acesso por CPF com validade de 30 dias</span>
    </div>

    <table class="col-2">
        <tr>
            <td style="width: 40%;">
                <div class="mockup">
                    <div class="mockup-topo">
                        <span class="bolinha" style="background:#ef4444"></span>
                        <span class="bolinha" style="background:#f59e0b"></span>
                        <span class="bolinha" style="background:#10b981"></span>
                        <span class="url">/avaliacao/{token}</span>
                    </div>
                    <div class="mockup-conteudo" style="padding:20px;">
                        <div style="text-align:center; margin-bottom:16px;">
                            <img src="file://{{ public_path('brasao.png') }}" style="height:45px;">
                            <div style="color:#fff; font-size:13px; font-weight:bold; margin-top:6px;">SAD Campo Bom</div>
                        </div>
                        <div style="background:#1e3a8a; border-radius:6px; padding:10px; margin-bottom:12px;">
                            <div style="color:#93c5fd; font-size:8px; text-transform:uppercase;">🔐 Verificação de identidade</div>
                            <div style="color:#e0e7ff; font-size:8.5px; margin-top:4px;">Informe seu CPF para acessar.</div>
                        </div>
                        <div style="color:#cbd5e1; font-size:9px; margin-bottom:4px;">CPF</div>
                        <div style="background:#1e293b; border:1px solid #334155; border-radius:4px; padding:8px 10px; color:#94a3b8; font-size:9px; margin-bottom:14px;">000.000.000-00</div>
                        <div style="background:#1e40af; color:#fff; text-align:center; padding:10px; border-radius:4px; font-weight:bold; font-size:10px;">Verificar e Acessar</div>
                    </div>
                </div>
            </td>
            <td style="width: 60%; padding-left:15px;">
                <div class="secao-titulo">Como funciona</div>
                <div style="font-size:10px; color:#334155; line-height:1.7;">
                    <strong>1.</strong> Chefia assina a avaliação e clica em <strong>"Assinar e Enviar"</strong><br>
                    <strong>2.</strong> Sistema envia e-mail ao servidor com link pessoal<br>
                    <strong>3.</strong> Servidor abre o link e digita o CPF<br>
                    <strong>4.</strong> Acessa a avaliação para ver, assinar, defender e anexar cursos
                </div>

                <div class="caixa caixa-info" style="margin-top:12px; font-size:9.5px;">
                    <strong>🔒 Sem senha:</strong> o servidor não precisa ter login no sistema.
                    O link é pessoal, único e intransferível.
                </div>

                <div class="secao-titulo" style="margin-top:12px;">Características do link</div>
                <table style="width:100%; font-size:9.5px; border-collapse:collapse;">
                    <tr>
                        <td style="padding:6px 10px; background:#f1f5f9; width:55%; border-radius:4px 0 0 4px;"><strong>Formato</strong></td>
                        <td style="padding:6px 10px; background:#f8fafc; border-radius:0 4px 4px 0;">Token aleatório de 64 caracteres</td>
                    </tr>
                    <tr><td colspan="2" style="height:4px;"></td></tr>
                    <tr>
                        <td style="padding:6px 10px; background:#f1f5f9; border-radius:4px 0 0 4px;"><strong>Validade</strong></td>
                        <td style="padding:6px 10px; background:#f8fafc; border-radius:0 4px 4px 0;">30 dias (renovável)</td>
                    </tr>
                    <tr><td colspan="2" style="height:4px;"></td></tr>
                    <tr>
                        <td style="padding:6px 10px; background:#f1f5f9; border-radius:4px 0 0 4px;"><strong>Autenticação</strong></td>
                        <td style="padding:6px 10px; background:#f8fafc; border-radius:0 4px 4px 0;">CPF completo</td>
                    </tr>
                    <tr><td colspan="2" style="height:4px;"></td></tr>
                    <tr>
                        <td style="padding:6px 10px; background:#f1f5f9; border-radius:4px 0 0 4px;"><strong>Registro</strong></td>
                        <td style="padding:6px 10px; background:#f8fafc; border-radius:0 4px 4px 0;">IP, data/hora e hash de integridade</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="slide-numero">11</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 12 — MINHA AVALIAÇÃO ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">10. Tela "Minha Avaliação"</span>
        <span class="subtitulo">O servidor vê as notas, assinaturas e histórico</span>
    </div>

    <div class="mockup">
        <div class="mockup-topo">
            <span class="bolinha" style="background:#ef4444"></span>
            <span class="bolinha" style="background:#f59e0b"></span>
            <span class="bolinha" style="background:#10b981"></span>
            <span class="url">/avaliacao/{token} → aba Minha Avaliação</span>
        </div>
        <div class="mockup-conteudo">
            <div style="background:#1e293b; border-radius:6px; padding:8px 12px; margin-bottom:10px;">
                <span style="color:#fff; font-size:9px; background:#1e40af; padding:4px 12px; border-radius:4px;">📋 Minha Avaliação</span>
                <span style="color:#cbd5e1; font-size:9px; padding:4px 12px; margin-left:6px;">🎓 Meus Cursos</span>
            </div>

            <table style="width:100%; margin-bottom:8px;">
                <tr>
                    <td style="background:#1e293b; border-radius:4px; padding:6px; font-size:8px; color:#cbd5e1; width:33%;">Ciclo: <strong style="color:#fff;">2026</strong></td>
                    <td style="width:6px;"></td>
                    <td style="background:#1e293b; border-radius:4px; padding:6px; font-size:8px; color:#cbd5e1; width:33%;">Avaliador: <strong style="color:#fff;">Maria Chefia</strong></td>
                    <td style="width:6px;"></td>
                    <td style="background:#1e293b; border-radius:4px; padding:6px; font-size:8px; color:#cbd5e1;">Status: <span class="badge b-warn">Notificada</span></td>
                </tr>
            </table>

            <table>
                <tr style="background:#1e293b;">
                    <td style="width:30px; color:#94a3b8; text-align:center;">1</td>
                    <td style="color:#cbd5e1; font-size:8px;">Assiduidade, pontualidade e registro funcional</td>
                    <td style="width:50px; text-align:center; color:#22d3ee; font-weight:bold;">9,0</td>
                </tr>
                <tr>
                    <td style="text-align:center; color:#94a3b8;">2</td>
                    <td style="color:#cbd5e1; font-size:8px;">Relacionamento interpessoal e comunicação</td>
                    <td style="text-align:center; color:#22d3ee; font-weight:bold;">8,5</td>
                </tr>
                <tr style="background:#1e293b;">
                    <td style="text-align:center; color:#94a3b8;">...</td>
                    <td style="color:#64748b; font-size:8px; font-style:italic;">(demais itens)</td>
                    <td></td>
                </tr>
            </table>

            <div style="background:#1e40af; border-radius:6px; padding:8px 15px; margin-top:8px;">
                <span style="color:#bfdbfe; font-size:9px;">NOTA TOTAL:</span>
                <span style="float:right; color:#fff; font-size:15px; font-weight:bold;">87,50</span>
            </div>

            <table style="width:100%; margin-top:10px; border-collapse:separate; border-spacing:5px 0;">
                <tr>
                    <td style="width:33%; background:#065f46; border-radius:4px; padding:6px; text-align:center;">
                        <div style="color:#a7f3d0; font-size:7px; text-transform:uppercase;">Chefia</div>
                        <div style="color:#fff; font-size:8.5px; font-weight:bold;">✓ Assinado</div>
                    </td>
                    <td style="width:33%; background:#334155; border-radius:4px; padding:6px; text-align:center;">
                        <div style="color:#94a3b8; font-size:7px; text-transform:uppercase;">Servidor</div>
                        <div style="color:#cbd5e1; font-size:8.5px;">Aguardando</div>
                    </td>
                    <td style="width:33%; background:#334155; border-radius:4px; padding:6px; text-align:center;">
                        <div style="color:#94a3b8; font-size:7px; text-transform:uppercase;">COMPAQ</div>
                        <div style="color:#cbd5e1; font-size:8.5px;">Aguardando</div>
                    </td>
                </tr>
            </table>

            <div style="text-align:center; margin-top:10px;">
                <span style="background:#059669; color:#fff; padding:7px 20px; border-radius:4px; font-size:9px; font-weight:bold;">✍️ Assinar Avaliação</span>
            </div>
        </div>
    </div>

    <div class="slide-numero">12</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 13 — MEUS CURSOS + DEFESA ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">11. Cursos e Defesa pelo Servidor</span>
        <span class="subtitulo">Duas ações disponíveis na mesma tela</span>
    </div>

    <table class="col-2">
        <tr>
            <td style="width: 50%;">
                <div class="secao-titulo">🎓 Meus Cursos — envio de certificados</div>
                <div class="mockup">
                    <div class="mockup-conteudo" style="padding:12px;">
                        <table style="width:100%; border-collapse:separate; border-spacing:5px 0; margin-bottom:10px;">
                            <tr>
                                <td style="width:33%; background:#1e3a8a; border-radius:4px; padding:6px; text-align:center;">
                                    <div style="color:#93c5fd; font-size:7px;">TOTAL HORAS</div>
                                    <div style="color:#fff; font-size:14px; font-weight:bold;">48h</div>
                                </td>
                                <td style="width:33%; background:#065f46; border-radius:4px; padding:6px; text-align:center;">
                                    <div style="color:#a7f3d0; font-size:7px;">APROVADAS</div>
                                    <div style="color:#fff; font-size:14px; font-weight:bold;">40h</div>
                                </td>
                                <td style="width:33%; background:#92400e; border-radius:4px; padding:6px; text-align:center;">
                                    <div style="color:#fde68a; font-size:7px;">EM ANÁLISE</div>
                                    <div style="color:#fff; font-size:14px; font-weight:bold;">1</div>
                                </td>
                            </tr>
                        </table>

                        <div style="background:#1e293b; border-radius:4px; padding:8px; margin-bottom:8px;">
                            <div style="color:#60a5fa; font-size:8px; font-weight:bold;">➕ Anexar novo certificado</div>
                        </div>
                        <div style="color:#94a3b8; font-size:8px; line-height:1.6;">
                            • Título do curso<br>
                            • Tipo: <strong style="color:#cbd5e1;">Curso</strong> ou <strong style="color:#cbd5e1;">Palestra</strong><br>
                            • Carga horária (mín. 4h)<br>
                            • Datas de início e conclusão<br>
                            • Arquivo PDF
                        </div>
                    </div>
                </div>
            </td>
            <td style="width: 50%; padding-left:12px;">
                <div class="secao-titulo">⚖️ Apresentar Defesa</div>
                <div class="mockup">
                    <div class="mockup-conteudo" style="padding:12px;">
                        <div style="background:#7c2d12; border-radius:4px; padding:8px; margin-bottom:8px;">
                            <div style="color:#fed7aa; font-size:9px; font-weight:bold;">⚖️ Apresentar Defesa</div>
                            <div style="color:#fdba74; font-size:7.5px; margin-top:2px;">Caso discorde da avaliação</div>
                        </div>
                        <div style="color:#cbd5e1; font-size:8px; margin-bottom:4px;">Texto da defesa *</div>
                        <div style="background:#1e293b; border:1px solid #334155; border-radius:4px; padding:8px; color:#64748b; font-size:8px; height:50px;">
                            Descreva seus argumentos, justificativas ou esclarecimentos...
                        </div>
                        <div style="color:#cbd5e1; font-size:8px; margin-top:8px; margin-bottom:4px;">Anexo (opcional)</div>
                        <div style="background:#1e293b; border:1px dashed #334155; border-radius:4px; padding:6px; color:#64748b; font-size:7.5px; text-align:center;">Selecionar arquivo PDF</div>
                        <div style="text-align:right; margin-top:8px;">
                            <span style="background:#ea580c; color:#fff; padding:5px 12px; border-radius:4px; font-size:8px; font-weight:bold;">Apresentar Defesa</span>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table class="col-2" style="margin-top:10px;">
        <tr>
            <td style="width:50%;">
                <div class="caixa caixa-ok" style="font-size:9px;">
                    <strong>✓ Prazo:</strong> a defesa pode ser apresentada dentro do prazo de 3 dias definido pela chefia na notificação.
                </div>
            </td>
            <td style="width:50%; padding-left:12px;">
                <div class="caixa caixa-info" style="font-size:9px;">
                    <strong>ℹ️ Resultado:</strong> a COMPAQ analisa e emite parecer. O servidor vê o resultado no mesmo link.
                </div>
            </td>
        </tr>
    </table>

    <div class="slide-numero">13</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 14 — ADMIN ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">12. Painel do Administrador</span>
        <span class="subtitulo">Gestão de usuários, importação e auditoria</span>
    </div>

    <div class="mockup">
        <div class="mockup-topo">
            <span class="bolinha" style="background:#ef4444"></span>
            <span class="bolinha" style="background:#f59e0b"></span>
            <span class="bolinha" style="background:#10b981"></span>
            <span class="url">sad.campobom.rs.gov.br/admin</span>
        </div>
        <div class="mockup-conteudo">
            <div class="sidebar">
                <div class="item ativo">🏠 Painel de Controle</div>
                <div style="color:#94a3b8; font-size:7px; padding:4px 8px; text-transform:uppercase; margin-top:4px;">Cadastros</div>
                <div class="item">👥 Servidores</div>
                <div class="item">🏢 Secretarias</div>
                <div class="item">💼 Cargos</div>
                <div style="color:#94a3b8; font-size:7px; padding:4px 8px; text-transform:uppercase; margin-top:4px;">Administração</div>
                <div class="item">👤 Usuários</div>
                <div class="item">📥 Importar</div>
                <div class="item">🛡️ Auditoria</div>
            </div>
            <div class="main-area">
                <h4>Painel de Controle</h4>
                <table style="width:100%; border-collapse:separate; border-spacing:6px 0; margin-bottom:10px;">
                    <tr>
                        <td style="background:#1e40af; border-radius:6px; padding:8px; text-align:center; width:25%;">
                            <div style="color:#bfdbfe; font-size:7px; text-transform:uppercase;">Servidores</div>
                            <div style="color:#fff; font-size:16px; font-weight:bold;">487</div>
                        </td>
                        <td style="background:#1e293b; border-radius:6px; padding:8px; text-align:center; width:25%;">
                            <div style="color:#94a3b8; font-size:7px; text-transform:uppercase;">Usuários</div>
                            <div style="color:#fff; font-size:16px; font-weight:bold;">24</div>
                        </td>
                        <td style="background:#1e293b; border-radius:6px; padding:8px; text-align:center; width:25%;">
                            <div style="color:#94a3b8; font-size:7px; text-transform:uppercase;">Ações 24h</div>
                            <div style="color:#fff; font-size:16px; font-weight:bold;">156</div>
                        </td>
                        <td style="background:#1e293b; border-radius:6px; padding:8px; text-align:center; width:25%;">
                            <div style="color:#94a3b8; font-size:7px; text-transform:uppercase;">Papéis</div>
                            <div style="color:#fff; font-size:16px; font-weight:bold;">5</div>
                        </td>
                    </tr>
                </table>

                <h4 style="font-size:11px;">Últimas atividades</h4>
                <table>
                    <tr>
                        <th>Quando</th>
                        <th>Usuário</th>
                        <th>Ação</th>
                        <th>Entidade</th>
                    </tr>
                    <tr>
                        <td>há 5 min</td>
                        <td>Administrador</td>
                        <td><span class="badge b-ok">Criou</span></td>
                        <td>Servidor #487</td>
                    </tr>
                    <tr>
                        <td>há 12 min</td>
                        <td>Miriam Vilande</td>
                        <td><span class="badge b-warn">Alterou</span></td>
                        <td>Qualificação #42</td>
                    </tr>
                    <tr>
                        <td>há 1 hora</td>
                        <td>Maria Chefia</td>
                        <td><span class="badge b-warn">Alterou</span></td>
                        <td>Avaliação #315</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <table class="col-2" style="margin-top:10px;">
        <tr>
            <td style="width:33%;">
                <div class="caixa caixa-info" style="font-size:9px;">
                    <strong>👥 Usuários</strong><br>
                    Criar, editar, atribuir papéis. Senhas e permissões.
                </div>
            </td>
            <td style="width:33%;">
                <div class="caixa caixa-ok" style="font-size:9px;">
                    <strong>📥 Importar CSV</strong><br>
                    Cadastro em massa com modelo pronto. Ideal para os 2.000 servidores.
                </div>
            </td>
            <td style="width:34%;">
                <div class="caixa caixa-warn" style="font-size:9px;">
                    <strong>🛡️ Auditoria</strong><br>
                    Toda ação registrada com usuário, data, IP e antes/depois.
                </div>
            </td>
        </tr>
    </table>

    <div class="slide-numero">14</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 15 — PDF FINAL ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">13. Documento Final em PDF</span>
        <span class="subtitulo">Pronto para arquivar com validade jurídica</span>
    </div>

    <table class="col-2">
        <tr>
            <td style="width: 45%;">
                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:6px; padding:14px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
                    <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
                        <tr>
                            <td style="width:35px; vertical-align:middle;">
                                <img src="file://{{ public_path('brasao.png') }}" style="height:32px;">
                            </td>
                            <td style="text-align:center; vertical-align:middle;">
                                <div style="color:#1e40af; font-size:8px; font-weight:bold;">COMPAQ — Comissão Permanente de Avaliação</div>
                                <div style="color:#64748b; font-size:7px;">Prefeitura Municipal de Campo Bom / RS</div>
                                <div style="color:#1e40af; font-size:8px; font-weight:bold; margin-top:2px;">Avaliação de Desempenho — Ciclo 2026</div>
                            </td>
                        </tr>
                    </table>

                    <div style="border-top:1px solid #1e40af; padding-top:6px;">
                        <div style="background:#f1f5f9; padding:4px 8px; font-size:7.5px; color:#1e40af; font-weight:bold; margin-bottom:4px;">DADOS DO SERVIDOR</div>
                        <table style="width:100%; font-size:7px; color:#334155; border-collapse:collapse;">
                            <tr><td style="padding:2px 4px;"><strong>Nome:</strong> Vinicius Cardoso</td><td style="padding:2px 4px;"><strong>Matrícula:</strong> 0232</td></tr>
                            <tr><td style="padding:2px 4px;"><strong>Cargo:</strong> Técnico em Informática</td><td style="padding:2px 4px;"><strong>Classe:</strong> B</td></tr>
                        </table>

                        <div style="background:#f1f5f9; padding:4px 8px; font-size:7.5px; color:#1e40af; font-weight:bold; margin-top:8px; margin-bottom:4px;">ITENS AVALIADOS</div>
                        <table style="width:100%; font-size:6.5px; color:#334155; border-collapse:collapse;">
                            <tr><td style="padding:2px 4px;">1. Assiduidade...</td><td style="text-align:right; color:#1e40af; font-weight:bold;">9,0</td></tr>
                            <tr><td style="padding:2px 4px;">2. Relacionamento...</td><td style="text-align:right; color:#1e40af; font-weight:bold;">8,5</td></tr>
                            <tr><td style="padding:2px 4px;">...</td><td></td></tr>
                        </table>

                        <div style="background:#eff6ff; padding:4px 8px; text-align:right; margin-top:6px;">
                            <span style="font-size:7px; color:#1e40af;">NOTA TOTAL: </span>
                            <span style="font-size:11px; color:#1e40af; font-weight:bold;">87,50</span>
                        </div>

                        <div style="background:#f1f5f9; padding:4px 8px; font-size:7.5px; color:#1e40af; font-weight:bold; margin-top:8px; margin-bottom:4px;">ASSINATURAS</div>
                        <table style="width:100%; border-collapse:separate; border-spacing:3px 0;">
                            <tr>
                                <td style="width:33%; background:#f0fdf4; border:1px solid #86efac; padding:4px; font-size:6px; color:#065f46;">
                                    <div style="font-weight:bold;">CHEFIA</div>
                                    <div>Maria Chefia</div>
                                    <div>03/10/2026 14:23</div>
                                </td>
                                <td style="width:33%; background:#f0fdf4; border:1px solid #86efac; padding:4px; font-size:6px; color:#065f46;">
                                    <div style="font-weight:bold;">SERVIDOR</div>
                                    <div>Vinicius Cardoso</div>
                                    <div>03/10/2026 15:41</div>
                                </td>
                                <td style="width:33%; background:#f0fdf4; border:1px solid #86efac; padding:4px; font-size:6px; color:#065f46;">
                                    <div style="font-weight:bold;">COMPAQ</div>
                                    <div>Miriam Vilande</div>
                                    <div>04/10/2026 09:12</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            <td style="width: 55%; padding-left:15px;">
                <div class="secao-titulo">O que o PDF contém</div>
                <table style="width:100%; font-size:9.5px; border-collapse:collapse;">
                    <tr>
                        <td style="padding:6px 10px; background:#f8fafc; border-left:3px solid #1e40af; border-radius:4px;">
                            <strong>📋 Cabeçalho oficial</strong> — COMPAQ, Prefeitura, brasão e ciclo
                        </td>
                    </tr>
                    <tr><td style="height:5px;"></td></tr>
                    <tr>
                        <td style="padding:6px 10px; background:#f8fafc; border-left:3px solid #1e40af; border-radius:4px;">
                            <strong>👤 Dados do servidor</strong> — nome, matrícula, cargo, classe, lotação
                        </td>
                    </tr>
                    <tr><td style="height:5px;"></td></tr>
                    <tr>
                        <td style="padding:6px 10px; background:#f8fafc; border-left:3px solid #1e40af; border-radius:4px;">
                            <strong>📊 10 itens com notas e comentários</strong>
                        </td>
                    </tr>
                    <tr><td style="height:5px;"></td></tr>
                    <tr>
                        <td style="padding:6px 10px; background:#f8fafc; border-left:3px solid #1e40af; border-radius:4px;">
                            <strong>⚖️ Defesa e parecer da COMPAQ</strong> (quando existir)
                        </td>
                    </tr>
                    <tr><td style="height:5px;"></td></tr>
                    <tr>
                        <td style="padding:6px 10px; background:#f8fafc; border-left:3px solid #1e40af; border-radius:4px;">
                            <strong>🎓 Cursos aprovados</strong> com carga horária
                        </td>
                    </tr>
                    <tr><td style="height:5px;"></td></tr>
                    <tr>
                        <td style="padding:6px 10px; background:#f0fdf4; border-left:3px solid #10b981; border-radius:4px;">
                            <strong>✍️ 3 assinaturas</strong> com nome, data/hora, IP e hash
                        </td>
                    </tr>
                </table>

                <div class="caixa caixa-ok" style="margin-top:12px; font-size:9.5px;">
                    <strong>✓ Validade jurídica:</strong> as assinaturas contêm hash SHA-256, IP e data/hora.
                    Em caso de questionamento, é possível comprovar autenticidade.
                </div>
            </td>
        </tr>
    </table>

    <div class="slide-numero">15</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 16 — RESUMO ============ -->
<div class="slide">
    <div class="slide-header">
        <img src="file://{{ public_path('brasao.png') }}" class="brasao">
        <span class="titulo">14. Resumo do Sistema</span>
        <span class="subtitulo">Tudo o que o SAD faz em uma visão</span>
    </div>

    <table style="width:100%; border-collapse:separate; border-spacing:6px;">
        <tr>
            <td style="width:25%; background:#eff6ff; border-radius:6px; padding:12px; vertical-align:top; border-left:4px solid #3b82f6;">
                <div style="font-size:24px;">🎯</div>
                <div style="font-weight:bold; color:#1e40af; font-size:10.5px; margin:6px 0;">Avaliação</div>
                <div style="font-size:8.5px; color:#334155; line-height:1.5;">
                    Fluxo completo com 4 formulários, cálculo automático e assinatura digital.
                </div>
            </td>
            <td style="width:25%; background:#f0fdf4; border-radius:6px; padding:12px; vertical-align:top; border-left:4px solid #10b981;">
                <div style="font-size:24px;">🎓</div>
                <div style="font-weight:bold; color:#065f46; font-size:10.5px; margin:6px 0;">Qualificação</div>
                <div style="font-size:8.5px; color:#334155; line-height:1.5;">
                    Cursos e palestras com aprovação. Horas acumuladas para progressão.
                </div>
            </td>
            <td style="width:25%; background:#fef3c7; border-radius:6px; padding:12px; vertical-align:top; border-left:4px solid #f59e0b;">
                <div style="font-size:24px;">⚖️</div>
                <div style="font-weight:bold; color:#92400e; font-size:10.5px; margin:6px 0;">Defesa</div>
                <div style="font-size:8.5px; color:#334155; line-height:1.5;">
                    Protocolo com anexo. Parecer estruturado com 4 decisões.
                </div>
            </td>
            <td style="width:25%; background:#f3e8ff; border-radius:6px; padding:12px; vertical-align:top; border-left:4px solid #8b5cf6;">
                <div style="font-size:24px;">📄</div>
                <div style="font-weight:bold; color:#6b21a8; font-size:10.5px; margin:6px 0;">Documentos</div>
                <div style="font-size:8.5px; color:#334155; line-height:1.5;">
                    PDF oficial com brasão, notas, defesa, parecer e assinaturas.
                </div>
            </td>
        </tr>
        <tr><td colspan="4" style="height:6px;"></td></tr>
        <tr>
            <td style="background:#e0f2fe; border-radius:6px; padding:12px; vertical-align:top; border-left:4px solid #0ea5e9;">
                <div style="font-size:24px;">📧</div>
                <div style="font-weight:bold; color:#075985; font-size:10.5px; margin:6px 0;">Notificações</div>
                <div style="font-size:8.5px; color:#334155; line-height:1.5;">
                    4 tipos de e-mail automáticos em fila.
                </div>
            </td>
            <td style="background:#fef2f2; border-radius:6px; padding:12px; vertical-align:top; border-left:4px solid #dc2626;">
                <div style="font-size:24px;">🛡️</div>
                <div style="font-weight:bold; color:#7f1d1d; font-size:10.5px; margin:6px 0;">Auditoria</div>
                <div style="font-size:8.5px; color:#334155; line-height:1.5;">
                    Toda ação registrada com usuário, IP e antes/depois.
                </div>
            </td>
            <td style="background:#f0fdfa; border-radius:6px; padding:12px; vertical-align:top; border-left:4px solid #14b8a6;">
                <div style="font-size:24px;">💾</div>
                <div style="font-weight:bold; color:#115e59; font-size:10.5px; margin:6px 0;">Backup</div>
                <div style="font-size:8.5px; color:#334155; line-height:1.5;">
                    3 camadas: local + Samba diário + mensal.
                </div>
            </td>
            <td style="background:#faf5ff; border-radius:6px; padding:12px; vertical-align:top; border-left:4px solid #a855f7;">
                <div style="font-size:24px;">🔐</div>
                <div style="font-weight:bold; color:#6b21a8; font-size:10.5px; margin:6px 0;">Segurança</div>
                <div style="font-size:8.5px; color:#334155; line-height:1.5;">
                    HTTPS, rate limit, cookies seguros, senhas hasheadas.
                </div>
            </td>
        </tr>
    </table>

    <div class="caixa caixa-info" style="margin-top:14px;">
        <strong>🏛️ Endereço único:</strong> <code style="background:#fff; padding:2px 8px; border-radius:3px; color:#1e40af; font-weight:bold;">https://sad.campobom.rs.gov.br</code> — o sistema identifica o perfil e direciona automaticamente.
    </div>

    <div class="slide-numero">16</div>
    <div class="slide-rodape">SAD — Sistema de Avaliação de Desempenho</div>
</div>

<!-- ============ SLIDE 17 — ENCERRAMENTO ============ -->
<div class="capa">
    <div style="margin-top: 15mm;">
        <img src="file://{{ public_path('brasao.png') }}" alt="Brasão" style="height: 100px;">
        <h1 style="font-size: 42px; margin: 25px 0 8px 0;">OBRIGADO</h1>
        <div class="divisor"></div>
        <div class="subtitulo" style="font-size: 13px; line-height: 2;">
            <strong style="color: #60a5fa;">SAD — Sistema de Avaliação de Desempenho</strong><br>
            Prefeitura Municipal de Campo Bom / RS<br>
            Comissão Permanente de Avaliação e Qualificação — COMPAQ
        </div>
        <div style="margin-top: 30px; padding: 20px; background: #1e293b; border-radius: 8px; display: inline-block; text-align: left;">
            <div style="color: #94a3b8; font-size: 9px; text-transform: uppercase; margin-bottom: 6px;">Acesso ao sistema</div>
            <div style="color: #60a5fa; font-size: 13px; font-family: monospace; font-weight: bold;">https://sad.campobom.rs.gov.br</div>
        </div>
    </div>
    <div class="rodape-capa">
        Documento gerado em {{ now()->format('d/m/Y \à\s H:i') }}<br>
        Em caso de dúvidas, entre em contato com a equipe de TI.
    </div>
</div>

</body>
</html>
