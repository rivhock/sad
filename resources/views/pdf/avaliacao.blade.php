<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Avaliação de Desempenho</title>
    <style>
        @page { margin: 30px 40px; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
            line-height: 1.4;
        }

        /* ===== CABEÇALHO ===== */
        .header {
            border-bottom: 2px solid #1e40af;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table .logo-cell {
            width: 100px;
            text-align: center;
            vertical-align: middle;
            padding-right: 10px;
        }
        .header-table .brasao {
            max-width: 90px;
            max-height: 90px;
        }
        .header-table .texto-cell {
            text-align: center;
            vertical-align: middle;
        }
        .header-table .orgao {
            font-size: 12px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 4px;
        }
        .header-table .prefeitura {
            font-size: 11px;
            color: #444;
            margin-bottom: 10px;
        }
        .header-table .titulo-doc {
            font-size: 13px;
            font-weight: bold;
            color: #333;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
        }

        /* ===== SEÇÕES ===== */
        .section { margin-bottom: 18px; }
        .section-titulo {
            background-color: #f3f4f6;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 11px;
            color: #1e40af;
            border-left: 4px solid #1e40af;
            margin-bottom: 10px;
        }

        /* ===== TABELA DE DADOS ===== */
        table.dados {
            width: 100%;
            border-collapse: collapse;
        }
        table.dados td {
            padding: 5px 8px;
            vertical-align: top;
            border-bottom: 1px solid #eee;
        }
        table.dados td.label {
            font-weight: bold;
            color: #666;
            width: 80px;
            font-size: 10px;
            white-space: nowrap;
        }
        table.dados.dados-2col td.valor {
            width: 40%;
            font-size: 10px;
            padding-right: 20px;
        }

        /* ===== ITENS ===== */
        table.itens {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        table.itens th {
            background-color: #1e40af;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
        }
        table.itens th.center { text-align: center; }
        table.itens td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 10px;
        }
        table.itens td.num {
            text-align: center;
            font-weight: bold;
            color: #666;
            width: 30px;
        }
        table.itens td.nota {
            text-align: center;
            font-weight: bold;
            color: #1e40af;
            font-size: 13px;
            width: 60px;
        }
        .comentario {
            display: block;
            color: #666;
            font-style: italic;
            font-size: 9px;
            margin-top: 3px;
        }
        .nota-total {
            margin-top: 10px;
            padding: 10px 14px;
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            text-align: right;
        }
        .nota-total .label {
            font-size: 12px;
            color: #1e40af;
        }
        .nota-total .valor {
            font-size: 22px;
            font-weight: bold;
            color: #1e40af;
            margin-left: 10px;
        }

        /* ===== OBSERVAÇÕES ===== */
        .observacoes {
            margin-top: 10px;
            padding: 10px;
            background-color: #fef3c7;
            border: 1px solid #fde68a;
        }
        .observacoes .texto {
            color: #78350f;
            font-size: 10px;
            white-space: pre-line;
        }

        /* ===== DEFESA ===== */
        .defesa {
            margin-top: 10px;
            padding: 10px;
            background-color: #f3e8ff;
            border: 1px solid #d8b4fe;
        }
        .defesa .info {
            font-size: 9px;
            color: #6b21a8;
            margin-bottom: 6px;
        }
        .defesa .texto {
            font-size: 10px;
            color: #581c87;
            white-space: pre-line;
        }

        /* ===== ASSINATURAS ===== */
        .assinaturas { margin-top: 30px; }
        .assinaturas-tabela {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .assinaturas-tabela td {
            width: 33.33%;
            padding: 8px;
            vertical-align: top;
            border: 1px solid #ddd;
        }
        .assinaturas-tabela td.assinado {
            background-color: #f0fdf4;
            border: 1px solid #86efac;
        }
        .assinaturas-tabela td.pendente {
            background-color: #f9fafb;
            border: 1px dashed #d1d5db;
        }
        .assinatura-label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .assinatura-nome {
            font-size: 11px;
            font-weight: bold;
            color: #111;
            margin-bottom: 4px;
        }
        .assinatura-info {
            font-size: 8px;
            color: #666;
            line-height: 1.3;
        }
        .assinatura-info .hash {
            font-family: monospace;
            font-size: 7px;
            word-break: break-all;
            color: #888;
        }
        .pendente-texto {
            font-size: 9px;
            color: #999;
            font-style: italic;
        }

        /* ===== QUALIFICAÇÕES ===== */
        .qualificacoes table {
            width: 100%;
            border-collapse: collapse;
        }
        .qualificacoes table th {
            background-color: #f3f4f6;
            padding: 5px 8px;
            text-align: left;
            font-size: 9px;
            color: #374151;
        }
        .qualificacoes table td {
            padding: 5px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
        }
        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-ok { background-color: #d1fae5; color: #065f46; }
        .badge-pend { background-color: #fef3c7; color: #92400e; }
        .badge-rej { background-color: #fee2e2; color: #991b1b; }

        /* ===== RODAPÉ ===== */
        .rodape {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 8px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- ============ CABEÇALHO COM BRASÃO ============ --}}
    @php
        $brasaoPath = public_path('brasao.png');
        $temBrasao  = file_exists($brasaoPath);
    @endphp

    <div class="header">
        <table class="header-table">
            <tr>
                @if ($temBrasao)
                    <td class="logo-cell">
                        <img src="{{ $brasaoPath }}" class="brasao" alt="Brasão">
                    </td>
                @endif
                <td class="texto-cell">
                    <div class="orgao">COMPAQ — Comissão Permanente de Avaliação e Qualificação</div>
                    <div class="prefeitura">Prefeitura Municipal de Campo Bom / RS</div>
                    <div class="titulo-doc">
                        {{ $avaliacao->tipo === 'estagio_probatorio' ? 'Avaliação de Estágio Probatório' : 'Avaliação de Desempenho' }}
                        — {{ $avaliacao->ciclo?->nome }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ============ DADOS DO SERVIDOR ============ --}}
    <div class="section">
        <div class="section-titulo">DADOS DO SERVIDOR</div>
        <table class="dados dados-2col">
            <tr>
                <td class="label">Nome:</td>
                <td class="valor">{{ $avaliacao->servidor?->user?->nome }}</td>
                <td class="label">Matrícula:</td>
                <td class="valor">{{ $avaliacao->servidor?->user?->matricula }}</td>
            </tr>
            <tr>
                <td class="label">Cargo:</td>
                <td class="valor">{{ $avaliacao->servidor?->cargo?->nome ?? '—' }}</td>
                <td class="label">Categoria:</td>
                <td class="valor">{{ $avaliacao->servidor?->categoria?->label() ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Secretaria:</td>
                <td class="valor">{{ $avaliacao->servidor?->secretaria?->nome ?? '—' }}</td>
                <td class="label">Lotação:</td>
                <td class="valor">{{ $avaliacao->servidor?->lotacao?->nome ?? '—' }}</td>
            </tr>
        </table>
    </div>

    {{-- ============ DADOS DA AVALIAÇÃO ============ --}}
    <div class="section">
        <div class="section-titulo">DADOS DA AVALIAÇÃO</div>
        <table class="dados dados-2col">
            <tr>
                <td class="label">Ciclo:</td>
                <td class="valor">{{ $avaliacao->ciclo?->nome }}</td>
                <td class="label">Data:</td>
                <td class="valor">{{ $avaliacao->data_avaliacao?->format('d/m/Y H:i') ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Avaliador:</td>
                <td class="valor">{{ $avaliacao->avaliador?->nome ?? '—' }}</td>
                <td class="label">Formulário:</td>
                <td class="valor">{{ $avaliacao->formulario?->titulo }}</td>
            </tr>
        </table>
    </div>

    {{-- ============ ITENS AVALIADOS ============ --}}
    <div class="section">
        <div class="section-titulo">ITENS AVALIADOS</div>
        @php $respostas = $avaliacao->respostas->keyBy('item_id'); @endphp

        <table class="itens">
            <thead>
                <tr>
                    <th class="center" style="width: 30px;">Nº</th>
                    <th>Item</th>
                    <th class="center" style="width: 60px;">Nota</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($avaliacao->respostas->sortBy('ordem_item') as $r)
                    <tr>
                        <td class="num">{{ $r->ordem }}</td>
                        <td>
                            {{ $r->descricao }}
                            @if ($r->comentario)
                                <span class="comentario">Comentário: {{ $r->comentario }}</span>
                            @endif
                        </td>
                        <td class="nota">{{ number_format($r->nota, 1, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="text-align: center; color: #999;">Sem itens registrados.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="nota-total">
            <span class="label">NOTA TOTAL FINAL:</span>
            <span class="valor">{{ $avaliacao->nota_total !== null ? number_format($avaliacao->nota_total, 2, ',', '.') : '—' }}</span>
        </div>
    </div>

    {{-- ============ OBSERVAÇÕES ============ --}}
    @if ($avaliacao->observacoes)
        <div class="section">
            <div class="section-titulo">OBSERVAÇÕES ADICIONAIS</div>
            <div class="observacoes">
                <div class="texto">{{ $avaliacao->observacoes }}</div>
            </div>
        </div>
    @endif

    {{-- ============ DEFESA ============ --}}
    @if ($avaliacao->defesas->count())
        @php $defesa = $avaliacao->defesas->first(); @endphp
        <div class="section">
            <div class="section-titulo">DEFESA APRESENTADA PELO SERVIDOR</div>
            <div class="defesa">
                <div class="info">
                    Protocolada em <strong>{{ $defesa->data_protocolo?->format('d/m/Y \à\s H:i') }}</strong>
                </div>
                <div class="texto">{{ $defesa->texto }}</div>
                @if ($defesa->anexo)
                    <p style="font-size: 9px; margin: 8px 0 0 0; color: #6b21a8;">
                        📎 Anexo: {{ basename($defesa->anexo) }}
                    </p>
                @endif
            </div>
        </div>
    @endif

    {{-- ============ PARECER DA COMPAQ ============ --}}
    @if ($avaliacao->defesas->count())
        @php $defesa = $avaliacao->defesas->first(); @endphp
        @if ($defesa->parecer_decisao)
            <div class="section">
                <div class="section-titulo">PARECER DA COMPAQ</div>
                <div style="background-color: #eef2ff; border: 1px solid #c7d2fe; padding: 10px; border-radius: 3px;">

                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 8px;">
                        <tr>
                            <td style="font-size: 10px; color: #4338ca;">
                                <strong>Emitido em:</strong> {{ $defesa->parecer_em?->format('d/m/Y \à\s H:i') }}
                                @if ($defesa->parecerista)
                                    · <strong>Por:</strong> {{ $defesa->parecerista->nome }}
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <span style="display: inline-block; padding: 3px 10px; border-radius: 3px; font-size: 10px; font-weight: bold; color: white;
                                    @if($defesa->parecer_decisao === 'deferida') background-color: #16a34a;
                                    @elseif($defesa->parecer_decisao === 'parcialmente_deferida') background-color: #2563eb;
                                    @elseif($defesa->parecer_decisao === 'indeferida') background-color: #ca8a04;
                                    @else background-color: #dc2626;
                                    @endif">
                                    {{ $defesa->parecer_label }}
                                </span>
                            </td>
                        </tr>
                    </table>

                    <div style="font-size: 10px; color: #312e81; white-space: pre-line; background: white; padding: 8px; border-radius: 3px; border: 1px solid #e0e7ff;">
                        {{ $defesa->parecer_texto }}
                    </div>

                    @if ($defesa->parecer_nota_anterior !== null && $defesa->parecer_nota_nova !== null && $defesa->parecer_nota_anterior != $defesa->parecer_nota_nova)
                        <table style="width: 100%; margin-top: 8px;">
                            <tr>
                                <td style="font-size: 10px; color: #666;">
                                    <strong>Nota antes do parecer:</strong>
                                    <span style="text-decoration: line-through; color: #999;">
                                        {{ number_format($defesa->parecer_nota_anterior, 2, ',', '.') }}
                                    </span>
                                </td>
                                <td style="text-align: right; font-size: 12px; color: #16a34a;">
                                    <strong>Nota final após parecer:</strong>
                                    <span style="font-size: 14px; font-weight: bold;">
                                        {{ number_format($defesa->parecer_nota_nova, 2, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    @endif

                </div>
            </div>
        @endif
    @endif

    {{-- ============ QUALIFICAÇÕES ============ --}}
    @if ($avaliacao->qualificacoes->count())
        <div class="section qualificacoes">
            <div class="section-titulo">CERTIFICADOS DE QUALIFICAÇÃO ({{ $avaliacao->qualificacoes->count() }})</div>
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Instituição</th>
                        <th style="width: 50px;">Carga</th>
                        <th style="width: 70px;">Conclusão</th>
                        <th style="width: 60px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($avaliacao->qualificacoes as $q)
                        <tr>
                            <td>{{ $q->titulo }}</td>
                            <td>{{ $q->instituicao ?? '—' }}</td>
                            <td>{{ $q->carga_horaria }}h</td>
                            <td>{{ $q->data_fim?->format('d/m/Y') }}</td>
                            <td>
                                @if ($q->status === 'aprovado')
                                    <span class="badge badge-ok">Aprovado</span>
                                @elseif ($q->status === 'rejeitado')
                                    <span class="badge badge-rej">Rejeitado</span>
                                @else
                                    <span class="badge badge-pend">Em análise</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p style="font-size: 9px; margin-top: 6px; color: #666;">
                Total de horas aprovadas: <strong>{{ $avaliacao->qualificacoes->where('status','aprovado')->sum('carga_horaria') }}h</strong>
            </p>
        </div>
    @endif

    {{-- ============ ASSINATURAS ============ --}}
    <div class="section assinaturas">
        <div class="section-titulo">ASSINATURAS</div>

        @php
            $chefia   = $avaliacao->assinaturas->firstWhere('tipo_assinante', 'chefia');
            $avaliado = $avaliacao->assinaturas->firstWhere('tipo_assinante', 'avaliado');
            $compaq   = $avaliacao->assinaturas->firstWhere('tipo_assinante', 'compaq');
        @endphp

        <table class="assinaturas-tabela">
            <tr>
                <td class="{{ $chefia ? 'assinado' : 'pendente' }}">
                    <div class="assinatura-label">Chefia do Avaliado</div>
                    @if ($chefia)
                        <div class="assinatura-nome">{{ $chefia->user?->nome }}</div>
                        <div class="assinatura-info">
                            {{ $chefia->assinado_em?->format('d/m/Y H:i') }}<br>
                            IP: {{ $chefia->ip }}<br>
                            <span class="hash">{{ substr($chefia->hash, 0, 24) }}...</span>
                        </div>
                    @else
                        <div class="pendente-texto">Aguardando assinatura</div>
                    @endif
                </td>

                <td class="{{ $avaliado ? 'assinado' : 'pendente' }}">
                    <div class="assinatura-label">Servidor Avaliado</div>
                    @if ($avaliado)
                        <div class="assinatura-nome">{{ $avaliado->user?->nome }}</div>
                        <div class="assinatura-info">
                            {{ $avaliado->assinado_em?->format('d/m/Y H:i') }}<br>
                            IP: {{ $avaliado->ip }}<br>
                            <span class="hash">{{ substr($avaliado->hash, 0, 24) }}...</span>
                        </div>
                    @else
                        <div class="pendente-texto">Aguardando assinatura</div>
                    @endif
                </td>

                <td class="{{ $compaq ? 'assinado' : 'pendente' }}">
                    <div class="assinatura-label">Membro da COMPAQ</div>
                    @if ($compaq)
                        <div class="assinatura-nome">{{ $compaq->user?->nome }}</div>
                        <div class="assinatura-info">
                            {{ $compaq->assinado_em?->format('d/m/Y H:i') }}<br>
                            IP: {{ $compaq->ip }}<br>
                            <span class="hash">{{ substr($compaq->hash, 0, 24) }}...</span>
                        </div>
                    @else
                        <div class="pendente-texto">Aguardando assinatura</div>
                    @endif
                </td>
            </tr>
        </table>

        <p style="font-size: 9px; margin-top: 12px; color: #666;">
            <strong>Nota:</strong> O avaliado fica notificado de que terá <strong>03 (três) dias</strong>, a partir da data da avaliação,
            para apresentar defesa escrita, a qual deverá ser regularmente protocolada na COMPAQ.
        </p>
    </div>

    {{-- ============ RODAPÉ ============ --}}
    <div class="rodape">
        <p>Documento gerado eletronicamente em {{ now()->format('d/m/Y \à\s H:i') }} (horário de Brasília)</p>
        <p>SAD Campo Bom — Prefeitura Municipal de Campo Bom / RS</p>
        <p>ID da avaliação: #{{ $avaliacao->id }} · Hash de verificação: {{ substr(hash('sha256', $avaliacao->id . $avaliacao->created_at), 0, 16) }}</p>
    </div>

</body>
</html>
