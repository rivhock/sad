<div class="space-y-4">

    {{-- DEFESA APRESENTADA --}}
    <div class="p-4 rounded-lg bg-purple-50 dark:bg-purple-950 border border-purple-200 dark:border-purple-800">
        <div class="flex items-start gap-3">
            <span class="text-2xl">⚖️</span>
            <div class="flex-1">
                <div class="font-bold text-purple-900 dark:text-purple-100">Defesa apresentada pelo servidor</div>
                <div class="text-xs text-purple-600 dark:text-purple-400 mt-0.5">
                    Protocolada em {{ $defesa->data_protocolo?->format('d/m/Y \à\s H:i') }}
                </div>
            </div>
        </div>

        <div class="mt-3 text-sm text-purple-900 dark:text-purple-100 whitespace-pre-line bg-white dark:bg-gray-900 p-3 rounded border border-purple-100 dark:border-purple-900">
            {{ $defesa->texto }}
        </div>

        @if ($defesa->anexo)
            <a href="{{ $defesa->anexo_url }}" target="_blank"
               class="inline-block mt-3 text-xs px-3 py-1.5 rounded bg-purple-600 text-white hover:bg-purple-700">
                📎 Ver anexo da defesa
            </a>
        @endif
    </div>

    {{-- PARECER DA COMPAQ --}}
    @if ($defesa->parecer_decisao)
        <div class="p-4 rounded-lg bg-indigo-50 dark:bg-indigo-950 border-2 border-indigo-300 dark:border-indigo-700">
            <div class="flex flex-wrap items-start gap-3 mb-3">
                <span class="text-2xl">🛡️</span>
                <div class="flex-1">
                    <div class="font-bold text-indigo-900 dark:text-indigo-100">Parecer da COMPAQ</div>
                    <div class="text-xs text-indigo-600 dark:text-indigo-400 mt-0.5">
                        Emitido em {{ $defesa->parecer_em?->format('d/m/Y \à\s H:i') }}
                        @if ($defesa->parecerista)
                            por <strong>{{ $defesa->parecerista->nome }}</strong>
                        @endif
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold text-white whitespace-nowrap
                    @if($defesa->parecer_decisao === 'deferida') bg-green-600
                    @elseif($defesa->parecer_decisao === 'parcialmente_deferida') bg-blue-600
                    @elseif($defesa->parecer_decisao === 'indeferida') bg-yellow-600
                    @else bg-red-600
                    @endif">
                    {{ $defesa->parecer_label }}
                </span>
            </div>

            <div class="text-sm text-indigo-900 dark:text-indigo-100 whitespace-pre-line bg-white dark:bg-gray-900 p-3 rounded border border-indigo-100 dark:border-indigo-900">
                {{ $defesa->parecer_texto }}
            </div>

            @if ($defesa->parecer_nota_anterior !== null && $defesa->parecer_nota_nova !== null && $defesa->parecer_nota_anterior != $defesa->parecer_nota_nova)
                <div class="mt-3 p-3 rounded bg-white dark:bg-gray-900 border border-indigo-100 dark:border-indigo-900 flex items-center justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Ajuste de nota</span>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-400 line-through">
                            {{ number_format($defesa->parecer_nota_anterior, 2, ',', '.') }}
                        </span>
                        <span class="text-lg font-bold text-green-600 dark:text-green-400">
                            → {{ number_format($defesa->parecer_nota_nova, 2, ',', '.') }}
                        </span>
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="p-3 rounded-lg bg-yellow-50 dark:bg-yellow-950 border border-yellow-200 dark:border-yellow-800 text-sm text-yellow-800 dark:text-yellow-200">
            ⏳ Parecer ainda não emitido. Clique em <strong>"Analisar Defesa"</strong> para homologar.
        </div>
    @endif

</div>
