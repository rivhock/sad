<x-filament-panels::page>
    {{-- DADOS GERAIS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900">
            <div class="text-xs text-gray-500 uppercase">Servidor</div>
            <div class="font-semibold">{{ $record->servidor?->user?->nome }}</div>
            <div class="text-sm text-gray-500">
                Mat. {{ $record->servidor?->user?->matricula }} •
                {{ $record->servidor?->cargo?->nome ?? '—' }}
            </div>
            <div class="text-sm text-gray-500">
                {{ $record->servidor?->secretaria?->sigla }} — {{ $record->servidor?->lotacao?->nome ?? '—' }}
            </div>
        </div>

        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900">
            <div class="text-xs text-gray-500 uppercase">Ciclo / Formulário</div>
            <div class="font-semibold">{{ $record->ciclo?->nome }}</div>
            <div class="text-sm text-gray-500">{{ $record->formulario?->titulo }}</div>
            <div class="text-sm text-gray-500">
                {{ $record->tipo === 'estagio_probatorio' ? 'Estágio Probatório' : 'Avaliação de Desempenho' }}
            </div>
        </div>

        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900">
            <div class="text-xs text-gray-500 uppercase">Status / Nota</div>
            <div class="font-semibold">{{ $record->status?->label() }}</div>
            @if ($record->nota_total !== null)
                <div class="text-2xl font-bold text-primary-600 mt-1">
                    {{ number_format($record->nota_total, 2, ',', '.') }}
                </div>
            @endif
            @if ($record->data_avaliacao)
                <div class="text-xs text-gray-500 mt-1">
                    Avaliada em {{ $record->data_avaliacao->format('d/m/Y H:i') }}
                </div>
            @endif
        </div>
    </div>

    {{-- AVALIADOR --}}
    <div class="mt-6 p-4 rounded-lg bg-blue-50 dark:bg-blue-950 border border-blue-200 dark:border-blue-800">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <div class="text-xs text-blue-700 dark:text-blue-300 uppercase font-semibold">Avaliador responsável</div>
                <div class="font-semibold text-blue-900 dark:text-blue-100">{{ $record->avaliador?->nome ?? '—' }}</div>
            </div>
            @if ($record->periodo_inicio && $record->periodo_fim)
                <div class="text-sm text-blue-800 dark:text-blue-200">
                    Período: <strong>{{ $record->periodo_inicio->format('d/m/Y') }}</strong> a <strong>{{ $record->periodo_fim->format('d/m/Y') }}</strong>
                </div>
            @endif
        </div>
    </div>

    {{-- ITENS AVALIADOS --}}
    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-3">Itens Avaliados</h3>
        @php $respostas = $record->respostas->keyBy('item_id'); @endphp

        <div class="space-y-2">
            @forelse ($record->respostas->sortBy('ordem_item') as $r)
                <div class="p-3 rounded border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-8 text-center font-bold text-gray-500">{{ $r->ordem }}</div>
                        <div class="flex-1">
                            <div class="text-sm">{{ $r->descricao }}</div>
                            @if ($r->comentario)
                                <div class="mt-2 p-2 rounded bg-gray-50 dark:bg-gray-800 text-xs">
                                    <span class="font-semibold text-gray-600 dark:text-gray-400">Comentário do avaliador:</span>
                                    <span class="text-gray-700 dark:text-gray-300 italic">{{ $r->comentario }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <div class="text-2xl font-bold text-primary-600">
                                {{ number_format($r->nota, 1, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500">Sem itens registrados.</div>
            @endforelse
        </div>
    </div>

    {{-- OBSERVAÇÕES --}}
    @if ($record->observacoes)
        <div class="mt-6 p-4 rounded-lg bg-yellow-50 dark:bg-yellow-950 border border-yellow-200 dark:border-yellow-800">
            <div class="text-sm font-semibold mb-1 text-yellow-900 dark:text-yellow-100">Observações da chefia</div>
            <div class="text-sm text-yellow-900 dark:text-yellow-100 whitespace-pre-line">{{ $record->observacoes }}</div>
        </div>
    @endif

    {{-- DEFESA --}}
    @if ($record->defesas->count())
        @php $defesa = $record->defesas->first(); @endphp
        <div class="mt-6 p-4 rounded-lg bg-purple-50 dark:bg-purple-950 border border-purple-200 dark:border-purple-800">
            <div class="text-sm font-semibold mb-1 text-purple-900 dark:text-purple-100">
                ⚖️ Defesa apresentada pelo servidor
            </div>
            <div class="text-xs text-purple-700 dark:text-purple-300 mb-2">
                Protocolada em {{ $defesa->data_protocolo?->format('d/m/Y \à\s H:i') }}
            </div>
            <div class="text-sm text-purple-900 dark:text-purple-100 whitespace-pre-line bg-white dark:bg-gray-900 p-3 rounded">
                {{ $defesa->texto }}
            </div>
            @if ($defesa->anexo)
                <a href="{{ $defesa->anexo_url }}" target="_blank"
                   class="inline-block mt-2 text-xs px-3 py-1.5 rounded bg-purple-600 text-white hover:bg-purple-700">
                    📎 Ver anexo da defesa
                </a>
            @endif
        </div>
    @endif

    {{-- PARECER DA COMPAQ --}}
    @php $defesaComParecer = $record->defesas->firstWhere('parecer_decisao', '!=', null); @endphp

    @if ($defesaComParecer)
        <div class="mt-6 p-4 rounded-lg bg-indigo-50 dark:bg-indigo-950 border border-indigo-200 dark:border-indigo-800">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-lg">🛡️</span>
                <div>
                    <div class="font-bold text-indigo-900 dark:text-indigo-100">PARECER DA COMPAQ</div>
                    <div class="text-xs text-indigo-600 dark:text-indigo-400">
                        Emitido em {{ $defesaComParecer->parecer_em?->format('d/m/Y \à\s H:i') }}
                        por {{ $defesaComParecer->parecerista?->nome ?? '—' }}
                    </div>
                </div>
                <div class="ml-auto">
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-{{ $defesaComParecer->parecer_cor }}-600 text-white">
                        {{ $defesaComParecer->parecer_label }}
                    </span>
                </div>
            </div>

            <div class="text-sm text-indigo-900 dark:text-indigo-100 whitespace-pre-line bg-white dark:bg-gray-900 p-3 rounded">
                {{ $defesaComParecer->parecer_texto }}
            </div>

            @if ($defesaComParecer->parecer_nota_anterior !== null && $defesaComParecer->parecer_nota_nova !== null)
                <div class="mt-3 flex items-center justify-end gap-3 text-sm">
                    <span class="text-gray-500">Nota anterior: <strong class="line-through">{{ number_format($defesaComParecer->parecer_nota_anterior, 2, ',', '.') }}</strong></span>
                    <span class="text-green-600 font-bold text-lg">→ {{ number_format($defesaComParecer->parecer_nota_nova, 2, ',', '.') }}</span>
                </div>
            @endif
        </div>
    @endif

    {{-- CURSOS --}}
    @if ($record->qualificacoes->count())
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-3">
                🎓 Cursos apresentados ({{ $record->qualificacoes->count() }})
                <span class="text-sm font-normal text-gray-500">
                    — {{ $record->qualificacoes->where('status', 'aprovado')->sum('carga_horaria') }}h aprovadas
                </span>
            </h3>
            <div class="space-y-2">
                @foreach ($record->qualificacoes as $q)
                    <div class="p-3 rounded border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <div class="font-semibold text-sm">{{ $q->titulo }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $q->instituicao ?? '—' }} •
                                {{ \App\Models\Qualificacao::tipos()[$q->tipo] ?? $q->tipo }} •
                                <strong>{{ $q->carga_horaria }}h</strong> •
                                {{ $q->data_fim?->format('d/m/Y') }}
                            </div>
                            @if ($q->observacoes)
                                <div class="mt-1 text-xs text-gray-600 dark:text-gray-400 italic">
                                    {{ $q->observacoes }}
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($q->status === 'aprovado') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100
                                @elseif($q->status === 'rejeitado') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100
                                @endif">
                                {{ $q->label_status }}
                            </span>
                            <a href="{{ $q->arquivo_url }}" target="_blank"
                               class="text-xs px-2 py-1 rounded border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800">
                                Ver PDF
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ASSINATURAS --}}
    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-3">Assinaturas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $tipos = [
                    'chefia'   => 'Chefia do Avaliado',
                    'avaliado' => 'Servidor Avaliado',
                    'compaq'   => 'Membro da COMPAQ',
                ];
            @endphp
            @foreach ($tipos as $tipo => $label)
                @php $a = $record->assinaturas->firstWhere('tipo_assinante', $tipo); @endphp
                <div class="p-4 rounded-lg border {{ $a ? 'border-green-300 bg-green-50 dark:bg-green-950 dark:border-green-700' : 'border-dashed border-gray-300 bg-gray-50 dark:bg-gray-900 dark:border-gray-700' }}">
                    <div class="text-xs text-gray-500 uppercase font-semibold">{{ $label }}</div>
                    @if ($a)
                        <div class="font-semibold mt-1">{{ $a->user?->nome }}</div>
                        <div class="text-xs text-gray-500 mt-1 space-y-0.5">
                            <div>📅 {{ $a->assinado_em?->format('d/m/Y H:i') }}</div>
                            <div>🌐 IP: {{ $a->ip }}</div>
                            <div class="font-mono text-[10px] break-all">#{{ substr($a->hash, 0, 24) }}…</div>
                        </div>
                    @else
                        <div class="text-sm text-gray-400 mt-1 italic">Aguardando assinatura</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
