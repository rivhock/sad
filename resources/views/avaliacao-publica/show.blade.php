<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliação — {{ $avaliacao->servidor?->user?->nome }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: system-ui, -apple-system, "Segoe UI", sans-serif; }
        .tab-active { border-bottom-color: #2563eb; color: #2563eb; font-weight: 600; }
        details > summary { list-style: none; cursor: pointer; }
        details > summary::-webkit-details-marker { display: none; }
    </style>
    <style>
    /* Remove as setinhas de input[type=number] */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
        appearance: textfield;
    }
</style>
</head>
<body class="min-h-screen py-8 bg-gray-100 text-gray-900">
    <div class="max-w-4xl mx-auto px-4">

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">SAD Campo Bom</h1>
                    <p class="text-sm text-gray-500">Sistema de Avaliação de Desempenho</p>
                    <p class="text-xs text-gray-400 mt-1">Prefeitura Municipal de Campo Bom / RS</p>
                </div>
                <div class="text-right">
                    <div class="text-xs uppercase text-gray-500">Servidor</div>
                    <div class="font-semibold text-gray-900">{{ $avaliacao->servidor?->user?->nome }}</div>
                    <div class="text-xs text-gray-500">Mat. {{ $avaliacao->servidor?->user?->matricula }}</div>
                </div>
            </div>
        </div>

        @if (session('sucesso'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">✅ {{ session('sucesso') }}</div>
        @endif
        @if (session('erro'))
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">⚠️ {{ session('erro') }}</div>
        @endif

        <div class="bg-white rounded-t-lg shadow border-b border-gray-200">
            <div class="flex gap-1 px-4">
                <button type="button" data-tab="avaliacao" class="tab-btn px-4 py-3 text-sm text-gray-600 border-b-2 border-transparent hover:text-gray-900 tab-active">📋 Minha Avaliação</button>
                <button type="button" data-tab="cursos" class="tab-btn px-4 py-3 text-sm text-gray-600 border-b-2 border-transparent hover:text-gray-900">🎓 Meus Cursos
                    @if ($qualificacoes->count())
                        <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800">{{ $qualificacoes->count() }}</span>
                    @endif
                </button>
            </div>
        </div>

        {{-- ABA AVALIAÇÃO --}}
        <div id="tab-avaliacao" class="tab-content bg-white rounded-b-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="p-3 rounded border border-gray-200">
                    <div class="text-xs uppercase text-gray-500">Ciclo</div>
                    <div class="font-semibold">{{ $avaliacao->ciclo?->nome }}</div>
                </div>
                <div class="p-3 rounded border border-gray-200">
                    <div class="text-xs uppercase text-gray-500">Avaliador</div>
                    <div>{{ $avaliacao->avaliador?->nome ?? '—' }}</div>
                </div>
                <div class="p-3 rounded border border-gray-200">
                    <div class="text-xs uppercase text-gray-500">Status</div>
                    <div class="inline-block px-2 py-1 rounded text-xs font-semibold
                        @if($avaliacao->status?->value === 'concluida') bg-green-100 text-green-800
                        @elseif($avaliacao->status?->value === 'notificada') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $avaliacao->status?->label() }}
                    </div>
                </div>
            </div>

            <h2 class="text-lg font-bold mb-3">Itens Avaliados</h2>
            @php $respostas = $avaliacao->respostas->keyBy('item_id'); @endphp

            <div class="space-y-2">
                @forelse ($avaliacao->respostas->sortBy('ordem_item') as $r)
                    <div class="p-3 rounded border border-gray-200">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 text-center font-bold text-gray-500">{{ $r->ordem }}</div>
                            <div class="flex-1 text-sm">
                                {{ $r->descricao }}
                                @if ($r->comentario)
                                    <div class="mt-2 text-xs italic text-gray-500">Comentário: {{ $r->comentario }}</div>
                                @endif
                            </div>
                            <div class="flex-shrink-0 text-2xl font-bold text-blue-600">
                                {{ number_format($r->nota, 1, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500">Sem itens registrados.</div>
                @endforelse
            </div>

            <div class="mt-6 pt-4 border-t border-gray-200 flex items-center justify-between">
                <span class="text-lg font-semibold">Nota Total:</span>
                <span class="text-3xl font-bold text-blue-600">
                    {{ $avaliacao->nota_total !== null ? number_format($avaliacao->nota_total, 2, ',', '.') : '—' }}
                </span>
            </div>

            @if ($avaliacao->observacoes)
                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="font-bold text-yellow-900 mb-1">Observações da Chefia</div>
                    <p class="text-sm text-yellow-900 whitespace-pre-line">{{ $avaliacao->observacoes }}</p>
                </div>
            @endif


            {{-- DEFESA --}}
            @php
                $defesa = $avaliacao->defesas->first();
                $podeDefender = $avaliacao->status?->value === 'notificada'
                    && (! $avaliacao->prazo_defesa || $avaliacao->prazo_defesa->isFuture())
                    && ! $defesa;
            @endphp

            @if ($defesa)
                <div class="mt-6 p-4 rounded-lg bg-purple-50 border border-purple-200">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">📝</span>
                        <span class="font-bold text-purple-900">Defesa apresentada</span>
                    </div>
                    <div class="text-xs text-purple-700 mb-2">
                        Protocolada em <strong>{{ $defesa->data_protocolo?->format('d/m/Y \à\s H:i') }}</strong>
                    </div>
                    <div class="text-sm text-purple-900 whitespace-pre-line bg-white p-3 rounded border border-purple-100">{{ $defesa->texto }}</div>
                    @if ($defesa->anexo_url)
                        <a href="{{ $defesa->anexo_url }}" target="_blank"
                           class="inline-block mt-3 text-xs px-3 py-1.5 rounded bg-purple-600 text-white hover:bg-purple-700">
                            📎 Ver anexo
                        </a>
                    @endif
                </div>

                @php $temParecer = $defesa->parecer_decisao !== null; @endphp
                @if ($temParecer)
                    <div class="mt-4 p-4 rounded-lg bg-indigo-50 border-2 border-indigo-300">
                        <div class="flex items-start gap-3 mb-3">
                            <span class="text-2xl">🛡️</span>
                            <div class="flex-1">
                                <div class="font-bold text-indigo-900">Parecer da COMPAQ</div>
                                <div class="text-xs text-indigo-600">
                                    Emitido em {{ $defesa->parecer_em?->format('d/m/Y \à\s H:i') }}
                                    @if ($defesa->parecerista)
                                        por {{ $defesa->parecerista->nome }}
                                    @endif
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold text-white
                                @if($defesa->parecer_decisao === 'deferida') bg-green-600
                                @elseif($defesa->parecer_decisao === 'parcialmente_deferida') bg-blue-600
                                @elseif($defesa->parecer_decisao === 'indeferida') bg-yellow-600
                                @else bg-red-600
                                @endif">
                                {{ $defesa->parecer_label }}
                            </span>
                        </div>

                        <div class="text-sm text-indigo-900 whitespace-pre-line bg-white p-3 rounded border border-indigo-100">
                            {{ $defesa->parecer_texto }}
                        </div>

                        @if ($defesa->parecer_nota_anterior !== null && $defesa->parecer_nota_nova !== null && $defesa->parecer_nota_anterior != $defesa->parecer_nota_nova)
                            <div class="mt-3 p-3 rounded bg-white border border-indigo-100 flex items-center justify-between">
                                <span class="text-sm text-gray-600">Nota após parecer:</span>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm text-gray-400 line-through">
                                        {{ number_format($defesa->parecer_nota_anterior, 2, ',', '.') }}
                                    </span>
                                    <span class="text-lg font-bold text-green-600">
                                        → {{ number_format($defesa->parecer_nota_nova, 2, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            @elseif ($podeDefender)
                <div class="mt-6 border-2 border-orange-300 rounded-lg bg-orange-50">
                    <div class="p-4 border-b border-orange-200">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">⚖️</span>
                            <span class="font-bold text-orange-900 text-lg">Apresentar Defesa</span>
                        </div>
                        <p class="text-sm text-orange-800 mt-1">
                            Caso discorde da avaliação, você pode apresentar defesa escrita com esclarecimentos.
                            @if ($avaliacao->prazo_defesa)
                                O prazo encerra em <strong>{{ $avaliacao->prazo_defesa->format('d/m/Y \à\s H:i') }}</strong>.
                            @endif
                        </p>
                    </div>
                    <form method="POST" action="{{ route('avaliacao.publica.defesa.store', $token) }}"
                          enctype="multipart/form-data"
                          class="p-4 space-y-4"
                          onsubmit="return confirm('Confirma o envio da defesa? Após enviar, não poderá alterar.');">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Texto da defesa / esclarecimentos *
                            </label>
                            <textarea name="texto" rows="6" required minlength="20" maxlength="5000"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400"
                                      placeholder="Descreva seus argumentos, justificativas ou esclarecimentos...">{{ old('texto') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Mínimo 20 caracteres · Máximo 5000 caracteres.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Anexo (opcional)
                            </label>
                            <input type="file" name="anexo" accept="application/pdf"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:rounded file:border-0 file:bg-orange-100 file:text-orange-700">
                            <p class="text-xs text-gray-500 mt-1">PDF, máximo 10 MB.</p>
                        </div>

                        <div class="text-right">
                            <button type="submit"
                                    class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition">
                                Apresentar Defesa
                            </button>
                        </div>
                    </form>
                </div>
            @elseif ($avaliacao->status?->value === 'notificada' && $avaliacao->prazo_defesa && $avaliacao->prazo_defesa->isPast())
                <div class="mt-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                    ⏰ O prazo para apresentar defesa expirou em {{ $avaliacao->prazo_defesa->format('d/m/Y H:i') }}.
                </div>
            @endif

            <h3 class="text-lg font-bold mt-6 mb-3">Assinaturas</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @php $tipos = ['chefia' => 'Chefia', 'avaliado' => 'Servidor', 'compaq' => 'COMPAQ']; @endphp
                @foreach ($tipos as $tipo => $label)
                    @php $a = $avaliacao->assinaturas->firstWhere('tipo_assinante', $tipo); @endphp
                    <div class="p-3 rounded-lg border {{ $a ? 'border-green-300 bg-green-50' : 'border-dashed border-gray-300 bg-gray-50' }}">
                        <div class="text-xs uppercase text-gray-600 font-semibold">{{ $label }}</div>
                        @if ($a)
                            <div class="font-semibold mt-1 text-sm">{{ $a->user?->nome }}</div>
                            <div class="text-xs text-gray-600 mt-1">{{ $a->assinado_em?->format('d/m/Y H:i') }}</div>
                        @else
                            <div class="text-xs text-gray-400 mt-1 italic">Aguardando</div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if (! $jaAssinou)
                @if ($assinaturaChefia)
                    <div class="mt-6 p-6 rounded-lg bg-gray-50 border border-gray-200 text-center">
                        <h3 class="font-bold text-lg mb-2">Confirmar Ciência</h3>
                        <p class="text-sm text-gray-600 mb-4">Ao assinar, você declara ciência da avaliação.</p>
                        <form method="POST" action="{{ route('avaliacao.publica.assinar', $token) }}" onsubmit="return confirm('Confirma a assinatura?');">
                            @csrf
                            <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">✍️ Assinar Avaliação</button>
                        </form>
                    </div>
                @else
                    <div class="mt-6 p-4 rounded-lg bg-gray-50 border border-gray-200 text-center text-sm text-gray-600">⏳ A chefia ainda não assinou esta avaliação.</div>
                @endif
            @else
                <div class="mt-6 p-4 rounded-lg bg-green-50 border border-green-200 text-center text-green-800 font-semibold">✅ Você já assinou esta avaliação.</div>
            @endif

            @if (isset($avaliacoesAnteriores) && $avaliacoesAnteriores->count() > 0)
                <div class="mt-8 pt-6 border-t-2 border-gray-300">
                    <h3 class="text-lg font-bold mb-1">📁 Avaliações anteriores</h3>
                    <p class="text-xs text-gray-500 mb-4">Histórico de avaliações já concluídas ({{ $avaliacoesAnteriores->count() }} registro(s)).</p>
                    <div class="space-y-2">
                        @foreach ($avaliacoesAnteriores as $ant)
                            <details class="border border-gray-200 rounded-lg">
                                <summary class="p-4 flex items-center justify-between hover:bg-gray-50">
                                    <div>
                                        <div class="font-semibold text-sm">{{ $ant->ciclo?->nome }}</div>
                                        @if ($ant->periodo_inicio && $ant->periodo_fim)
                                            <div class="text-xs text-gray-500">
                                                {{ $ant->periodo_inicio->format('d/m/Y') }} a {{ $ant->periodo_fim->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500">Nota final</div>
                                            <div class="text-xl font-bold text-blue-600">{{ $ant->nota_total !== null ? number_format($ant->nota_total, 2, ',', '.') : '—' }}</div>
                                        </div>
                                        <div class="text-gray-400">▾</div>
                                    </div>
                                </summary>
                                <div class="p-4 border-t border-gray-100 bg-gray-50">
                                    <div class="space-y-1 mb-3">
                                        @php $resps = $ant->respostas->keyBy('item_id'); @endphp
                                        @foreach ($ant->formulario->itens as $it)
                                            @php $rr = $resps->get($it->id); @endphp
                                            <div class="flex items-start gap-2 text-xs">
                                                <div class="w-6 text-center text-gray-400">{{ $it->ordem }}</div>
                                                <div class="flex-1 text-gray-700">{{ $it->descricao }}</div>
                                                <div class="font-bold text-blue-600">{{ $rr ? number_format($rr->nota, 1, ',', '.') : '—' }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-xs text-gray-500 pt-3 border-t border-gray-200">
                                        Avaliador: <strong>{{ $ant->avaliador?->nome }}</strong>
                                    </div>
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ABA CURSOS --}}
        <div id="tab-cursos" class="tab-content bg-white rounded-b-lg shadow p-6 hidden">
            <div class="grid grid-cols-3 gap-3 mb-6">
                <div class="p-3 rounded-lg bg-blue-50 border border-blue-200">
                    <div class="text-xs uppercase text-blue-700 font-semibold">Total de horas</div>
                    <div class="text-2xl font-bold text-blue-900">{{ $totalHoras }} h</div>
                </div>
                <div class="p-3 rounded-lg bg-green-50 border border-green-200">
                    <div class="text-xs uppercase text-green-700 font-semibold">Horas aprovadas</div>
                    <div class="text-2xl font-bold text-green-900">{{ $horasAprovadas }} h</div>
                </div>
                <div class="p-3 rounded-lg bg-yellow-50 border border-yellow-200">
                    <div class="text-xs uppercase text-yellow-700 font-semibold">Em análise</div>
                    <div class="text-2xl font-bold text-yellow-900">{{ $totalPendentes }}</div>
                </div>
            </div>

            <details class="mb-6 border border-gray-200 rounded-lg" {{ $qualificacoes->isEmpty() ? 'open' : '' }}>
                <summary class="px-4 py-3 font-semibold bg-gray-50 rounded-t-lg hover:bg-gray-100">➕ Anexar novo certificado</summary>
                <form method="POST" action="{{ route('avaliacao.publica.qualificacao.store', $token) }}" enctype="multipart/form-data" class="p-4 space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Título do curso/palestra *</label>
                            <input type="text" name="titulo" required maxlength="255" value="{{ old('titulo') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instituição (opcional)</label>
                            <input type="text" name="instituicao" maxlength="255" value="{{ old('instituicao') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                            <select name="tipo" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                @foreach (\App\Models\Qualificacao::tipos() as $k => $v)
                                    <option value="{{ $k }}" {{ old('tipo') === $k ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Carga horária (horas) *</label>
                            <input type="number" name="carga_horaria" required min="4" max="1000" value="{{ old('carga_horaria') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Mínimo 4 horas.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Início *</label>
                            <input type="date" name="data_inicio" required value="{{ old('data_inicio') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Conclusão *</label>
                            <input type="date" name="data_fim" required value="{{ old('data_fim') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Certificado (PDF) *</label>
                            <input type="file" name="arquivo" accept="application/pdf" required class="w-full px-3 py-2 border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">Somente PDF, máximo 10 MB.</p>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="p-3 rounded bg-red-50 border border-red-200 text-red-800 text-sm">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="text-right">
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">Enviar Certificado</button>
                    </div>
                </form>
            </details>

            <h3 class="font-bold text-lg mb-3">Certificados enviados ({{ $qualificacoes->count() }})</h3>

            @if ($qualificacoes->isEmpty())
                <div class="p-6 text-center text-gray-500 text-sm border border-dashed border-gray-300 rounded-lg">Você ainda não anexou certificados.</div>
            @else
                @foreach ($qualificacoesPorAno as $ano => $itens)
                    <div class="mb-6">
                        <div class="text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-gray-200 text-gray-700">{{ $ano }}</span>
                            <span class="text-xs font-normal text-gray-500">{{ $itens->sum('carga_horaria') }} h • {{ $itens->count() }} certificado(s)</span>
                        </div>
                        <div class="space-y-3">
                            @foreach ($itens as $q)
                                <div class="p-4 rounded-lg border border-gray-200 flex flex-wrap items-start gap-3">
                                    <div class="flex-1 min-w-[200px]">
                                        <div class="font-semibold">{{ $q->titulo }}</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $q->instituicao ?? '—' }} • {{ \App\Models\Qualificacao::tipos()[$q->tipo] ?? $q->tipo }} • <strong>{{ $q->carga_horaria }} h</strong> • {{ $q->data_inicio?->format('d/m/Y') }} a {{ $q->data_fim?->format('d/m/Y') }}
                                        </div>
                                        @if ($q->observacoes)
                                            <div class="mt-2 p-2 rounded bg-gray-50 text-xs"><strong>Obs.:</strong> {{ $q->observacoes }}</div>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-1 rounded text-xs font-semibold
                                            @if($q->status === 'aprovado') bg-green-100 text-green-800
                                            @elseif($q->status === 'rejeitado') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ $q->label_status }}
                                        </span>
                                        <a href="{{ $q->arquivo_url }}" target="_blank" class="text-xs px-2 py-1 rounded border border-gray-300 hover:bg-gray-50">Ver PDF</a>
                                        @if ($q->status === 'pendente')
                                            <form method="POST" action="{{ route('avaliacao.publica.qualificacao.destroy', [$token, $q->id]) }}" onsubmit="return confirm('Remover?');" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-xs px-2 py-1 rounded border border-red-300 text-red-600 hover:bg-red-50">Excluir</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="text-center text-xs text-gray-500 py-8">
            <p>SAD Campo Bom — Prefeitura Municipal de Campo Bom/RS</p>
            <p class="mt-1">Este link é pessoal e intransferível.</p>
        </div>
    </div>

    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('tab-active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
                btn.classList.add('tab-active');
                document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
            });
        });
    </script>
</body>
</html>
