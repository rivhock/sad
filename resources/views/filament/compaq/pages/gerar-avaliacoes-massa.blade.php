<x-filament-panels::page>
    <form wire:submit.prevent>
        {{ $this->form }}
    </form>

    {{-- BOTÕES DE AÇÃO --}}
    <div class="mt-6 flex flex-wrap items-center gap-3">
        <button type="button"
                wire:click="simular"
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 transition disabled:opacity-50">
            <span wire:loading.remove wire:target="simular">🔎 Simular</span>
            <span wire:loading wire:target="simular">⏳ Calculando...</span>
        </button>

        <button type="button"
                wire:click="gerar"
                wire:confirm="Confirma a geração de avaliações em massa? Esta ação não pode ser desfeita."
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-sm bg-success-600 hover:bg-success-700 text-white transition disabled:opacity-50"
                style="background-color: #059669;">
            <span wire:loading.remove wire:target="gerar">✨ Gerar Avaliações</span>
            <span wire:loading wire:target="gerar">⏳ Gerando...</span>
        </button>
    </div>

    {{-- Resultado da simulação --}}
    @if (! empty($simulacao))
        <div class="mt-6 p-5 rounded-lg bg-blue-50 dark:bg-blue-950 border border-blue-200 dark:border-blue-800">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-2xl">🔎</span>
                <h3 class="font-bold text-lg text-blue-900 dark:text-blue-100">Simulação</h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                <div class="p-3 rounded bg-white dark:bg-gray-900 border border-blue-100 dark:border-blue-900">
                    <div class="text-xs text-gray-500 uppercase">Servidores no filtro</div>
                    <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $simulacao['total_servidores'] }}</div>
                </div>
                <div class="p-3 rounded bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-800">
                    <div class="text-xs text-green-700 dark:text-green-300 uppercase">Seriam criadas</div>
                    <div class="text-2xl font-bold text-green-800 dark:text-green-100">{{ $simulacao['criadas'] }}</div>
                </div>
                <div class="p-3 rounded bg-yellow-50 dark:bg-yellow-950 border border-yellow-200 dark:border-yellow-800">
                    <div class="text-xs text-yellow-700 dark:text-yellow-300 uppercase">Já tinham</div>
                    <div class="text-2xl font-bold text-yellow-800 dark:text-yellow-100">{{ $simulacao['ignoradas'] }}</div>
                </div>
                <div class="p-3 rounded bg-orange-50 dark:bg-orange-950 border border-orange-200 dark:border-orange-800">
                    <div class="text-xs text-orange-700 dark:text-orange-300 uppercase">Sem chefia</div>
                    <div class="text-2xl font-bold text-orange-800 dark:text-orange-100">{{ $simulacao['sem_chefia'] }}</div>
                </div>
                <div class="p-3 rounded bg-red-50 dark:bg-red-950 border border-red-200 dark:border-red-800">
                    <div class="text-xs text-red-700 dark:text-red-300 uppercase">Sem formulário</div>
                    <div class="text-2xl font-bold text-red-800 dark:text-red-100">{{ $simulacao['sem_formulario'] }}</div>
                </div>
            </div>

            @if ($simulacao['criadas'] > 0)
                <div class="mt-4 text-sm text-blue-900 dark:text-blue-100">
                    ✅ Clique em <strong>"Gerar Avaliações"</strong> para confirmar a criação.
                </div>
            @else
                <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                    Nenhuma avaliação nova seria criada com esses filtros.
                </div>
            @endif
        </div>
    @endif

    {{-- Resultado da geração --}}
    @if (! empty($resultado))
        <div class="mt-6 p-5 rounded-lg bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-800">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-2xl">✅</span>
                <h3 class="font-bold text-lg text-green-900 dark:text-green-100">Geração concluída</h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                <div class="p-3 rounded bg-white dark:bg-gray-900 border border-green-100">
                    <div class="text-xs text-gray-500 uppercase">Servidores no filtro</div>
                    <div class="text-2xl font-bold text-gray-700 dark:text-gray-200">{{ $resultado['total_servidores'] }}</div>
                </div>
                <div class="p-3 rounded bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700">
                    <div class="text-xs text-green-800 dark:text-green-200 uppercase font-semibold">Criadas</div>
                    <div class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $resultado['criadas'] }}</div>
                </div>
                <div class="p-3 rounded bg-yellow-50 dark:bg-yellow-950 border border-yellow-200 dark:border-yellow-800">
                    <div class="text-xs text-yellow-700 dark:text-yellow-300 uppercase">Já tinham</div>
                    <div class="text-2xl font-bold text-yellow-800 dark:text-yellow-100">{{ $resultado['ignoradas'] }}</div>
                </div>
                <div class="p-3 rounded bg-orange-50 dark:bg-orange-950 border border-orange-200 dark:border-orange-800">
                    <div class="text-xs text-orange-700 dark:text-orange-300 uppercase">Sem chefia</div>
                    <div class="text-2xl font-bold text-orange-800 dark:text-orange-100">{{ $resultado['sem_chefia'] }}</div>
                </div>
                <div class="p-3 rounded bg-red-50 dark:bg-red-950 border border-red-200 dark:border-red-800">
                    <div class="text-xs text-red-700 dark:text-red-300 uppercase">Sem formulário</div>
                    <div class="text-2xl font-bold text-red-800 dark:text-red-100">{{ $resultado['sem_formulario'] }}</div>
                </div>
            </div>

            @if (! empty($resultado['detalhes']))
                <details class="mt-4">
                    <summary class="cursor-pointer font-semibold text-sm text-gray-700 dark:text-gray-300">
                        Ver detalhes ({{ count($resultado['detalhes']) }} servidor(es) ignorado(s))
                    </summary>
                    <ul class="mt-3 text-sm space-y-1 list-disc list-inside text-gray-600 dark:text-gray-400">
                        @foreach ($resultado['detalhes'] as $d)
                            <li>{{ $d }}</li>
                        @endforeach
                    </ul>
                </details>
            @endif
        </div>
    @endif
</x-filament-panels::page>
