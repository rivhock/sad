<x-filament-panels::page>
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

    <div class="mb-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-900 text-sm">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div><span class="text-gray-500">Servidor:</span> <strong>{{ $record->servidor?->user?->nome }}</strong></div>
            <div><span class="text-gray-500">Matrícula:</span> <strong>{{ $record->servidor?->user?->matricula }}</strong></div>
            <div><span class="text-gray-500">Ciclo:</span> <strong>{{ $record->ciclo?->nome }}</strong></div>
            <div><span class="text-gray-500">Formulário:</span> <strong>{{ $record->formulario?->titulo }}</strong></div>
        </div>
    </div>

    <form wire:submit="salvar">
        {{ $this->form }}
    </form>

    <div class="mt-4 p-4 rounded-lg bg-primary-50 dark:bg-primary-950 border border-primary-200 dark:border-primary-800">
        <div class="flex items-center justify-between">
            <span class="text-lg font-semibold text-primary-900 dark:text-primary-100">Nota Total:</span>
            <span class="text-3xl font-bold text-primary-700 dark:text-primary-300">
                {{ number_format($this->notaTotal, 2, ',', '.') }}
            </span>
        </div>
    </div>
</x-filament-panels::page>
