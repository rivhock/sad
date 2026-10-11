<x-filament-panels::page>
    <form wire:submit="importar">
        {{ $this->form }}
    </form>

    <x-filament-actions::modals />

    @if (! empty($resultado))
        <div class="mt-6 space-y-4">
            {{-- Resumo --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="p-4 rounded-lg bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-800">
                    <div class="text-xs uppercase text-green-700 dark:text-green-300 font-semibold">Criados</div>
                    <div class="text-3xl font-bold text-green-900 dark:text-green-100">{{ $resultado['criados'] }}</div>
                </div>
                <div class="p-4 rounded-lg bg-yellow-50 dark:bg-yellow-950 border border-yellow-200 dark:border-yellow-800">
                    <div class="text-xs uppercase text-yellow-700 dark:text-yellow-300 font-semibold">Ignorados</div>
                    <div class="text-3xl font-bold text-yellow-900 dark:text-yellow-100">{{ $resultado['ignorados'] }}</div>
                </div>
                <div class="p-4 rounded-lg bg-red-50 dark:bg-red-950 border border-red-200 dark:border-red-800">
                    <div class="text-xs uppercase text-red-700 dark:text-red-300 font-semibold">Erros</div>
                    <div class="text-3xl font-bold text-red-900 dark:text-red-100">{{ count($resultado['erros']) }}</div>
                </div>
            </div>

            @if (! empty($resultado['erros']))
                <div class="p-4 rounded-lg bg-red-50 dark:bg-red-950 border border-red-200 dark:border-red-800">
                    <div class="font-semibold text-red-900 dark:text-red-100 mb-2">Detalhes dos erros:</div>
                    <ul class="text-sm text-red-800 dark:text-red-200 space-y-1 list-disc list-inside">
                        @foreach ($resultado['erros'] as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif
</x-filament-panels::page>
