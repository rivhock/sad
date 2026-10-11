<div class="space-y-4">
    {{-- Defesa apresentada --}}
    <div class="p-4 rounded-lg bg-purple-50 dark:bg-purple-950 border border-purple-200 dark:border-purple-800">
        <div class="text-xs uppercase text-purple-700 dark:text-purple-300 font-semibold mb-1">
            ⚖️ Defesa apresentada pelo servidor
        </div>
        <div class="text-xs text-purple-600 dark:text-purple-400 mb-2">
            Protocolada em {{ $defesa->data_protocolo?->format('d/m/Y \à\s H:i') }}
        </div>
        <div class="text-sm text-purple-900 dark:text-purple-100 whitespace-pre-line bg-white dark:bg-gray-900 p-3 rounded border border-purple-100 dark:border-purple-900">
            {{ $defesa->texto }}
        </div>
        @if ($defesa->anexo)
            <a href="{{ $defesa->anexo_url }}" target="_blank"
               class="inline-block mt-2 text-xs px-3 py-1.5 rounded bg-purple-600 text-white hover:bg-purple-700">
                📎 Ver anexo da defesa
            </a>
        @endif
    </div>

    {{-- Nota atual --}}
    <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Nota atual da avaliação</span>
            <span class="text-2xl font-bold text-primary-600">
                {{ $avaliacao->nota_total !== null ? number_format($avaliacao->nota_total, 2, ',', '.') : '—' }}
            </span>
        </div>
    </div>

    {{-- Aviso --}}
    <div class="p-3 rounded-lg bg-blue-50 dark:bg-blue-950 border border-blue-200 dark:border-blue-800 text-xs text-blue-800 dark:text-blue-200">
        <strong>Como funciona:</strong> ao emitir o parecer, a avaliação volta para o servidor assinar a versão final (exceto em encaminhamento para PAD). Se você aceitar a defesa, pode ajustar as notas abaixo.
    </div>
</div>
