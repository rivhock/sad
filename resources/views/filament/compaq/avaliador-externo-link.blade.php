<div class="space-y-3">
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Envie este link para a <strong>chefia responsável pela avaliação</strong> de
        <strong>{{ $avaliacao->servidor?->user?->nome }}</strong>.
        Ela deverá informar nome completo e CPF para acessar.
    </p>

    @if ($avaliacao->token_avaliador)
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Link do avaliador externo</label>
            <textarea
                readonly
                rows="3"
                onclick="this.select(); this.setSelectionRange(0, 99999); try { document.execCommand('copy'); this.blur(); } catch(e) {}"
                class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs font-mono break-all resize-none cursor-pointer"
                title="Clique para selecionar e copiar"
            >{{ $avaliacao->link_avaliador_externo }}</textarea>
            <p class="text-xs text-gray-500 mt-1">💡 Clique dentro da caixa para copiar automaticamente.</p>
        </div>

        @if ($avaliacao->token_avaliador_expira_em)
            <p class="text-xs text-gray-500">
                Expira em: <strong>{{ $avaliacao->token_avaliador_expira_em->format('d/m/Y H:i') }}</strong>
            </p>
        @endif
    @else
        <div class="p-3 rounded bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm">
            Nenhum link gerado ainda. Use o botão <strong>"Gerar link do avaliador"</strong> para criar.
        </div>
    @endif
</div>
