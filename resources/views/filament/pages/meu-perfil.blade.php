<x-filament-panels::page>
    <div class="mb-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-950 border border-blue-200 dark:border-blue-800">
        <div class="flex items-start gap-3">
            <span class="text-2xl">👤</span>
            <div>
                <div class="font-bold text-blue-900 dark:text-blue-100">Meus Dados</div>
                <div class="text-sm text-blue-800 dark:text-blue-200">
                    Você pode atualizar seu <strong>nome</strong> e <strong>e-mail</strong>.
                    Os demais dados funcionais são gerenciados pela COMPAQ.
                    Para alterar a senha, preencha os 3 campos da seção "Segurança".
                </div>
            </div>
        </div>
    </div>

    <form wire:submit="salvar">
        {{ $this->form }}

        <div class="mt-6 flex justify-end gap-3">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg font-semibold text-sm bg-success-600 hover:bg-success-700 text-white transition disabled:opacity-50"
                    style="background-color: #059669;">
                <span wire:loading.remove wire:target="salvar">💾 Salvar Alterações</span>
                <span wire:loading wire:target="salvar">⏳ Salvando...</span>
            </button>
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
