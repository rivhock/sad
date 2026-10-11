<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $avaliacao->servidor?->user?->nome }} — Avaliação</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: system-ui, -apple-system, "Segoe UI", sans-serif; }</style>
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

        <div class="mb-4">
            <a href="{{ route('avaliador.externo.lote.show', $token) }}"
               class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800">
                ← Voltar para a lista
            </a>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('brasao.png') }}" class="h-10" alt="Brasão">
                    <div>
                        <h1 class="text-lg font-bold text-blue-800">SAD Campo Bom</h1>
                        <p class="text-xs text-gray-500">Avaliador: {{ $nomeChefia }}</p>
                    </div>
                </div>
                <div class="text-right">
                    @if ($jaAssinada)
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">✅ Assinada</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">⏳ Aguardando assinatura</span>
                    @endif
                </div>
            </div>
        </div>

        @if (session('sucesso'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">✅ {{ session('sucesso') }}</div>
        @endif
        @if (session('erro'))
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">⚠️ {{ session('erro') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Dados do Servidor</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div><span class="text-gray-500">Nome:</span> <strong>{{ $avaliacao->servidor?->user?->nome }}</strong></div>
                <div><span class="text-gray-500">Matrícula:</span> {{ $avaliacao->servidor?->user?->matricula }}</div>
                <div><span class="text-gray-500">Cargo:</span> {{ $avaliacao->servidor?->cargo?->nome ?? '—' }}</div>
                <div><span class="text-gray-500">Lotação:</span> {{ $avaliacao->servidor?->lotacao?->nome ?? '—' }}</div>
                <div><span class="text-gray-500">Ciclo:</span> {{ $avaliacao->ciclo?->nome }}</div>
                <div><span class="text-gray-500">Tipo:</span> {{ $avaliacao->tipo === 'estagio_probatorio' ? 'Estágio Probatório' : 'Avaliação de Desempenho' }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('avaliador.externo.lote.avaliacao.salvar', [$token, $avaliacao->id]) }}">
            @csrf

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-bold mb-4">Itens a Avaliar</h2>
                <p class="text-sm text-gray-600 mb-6">Atribua uma nota de 0 a 10 para cada item.</p>

                @php $respostas = $avaliacao->respostas->keyBy('item_id'); @endphp

                <div class="space-y-4">
                    @foreach ($avaliacao->formulario->itens as $item)
                        @php $r = $respostas->get($item->id); @endphp
                        <div class="p-4 rounded-lg border border-gray-200 bg-gray-50">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-bold flex items-center justify-center">
                                    {{ $item->ordem }}
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm mb-3">{{ $item->descricao }}</div>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">Nota *</label>
                                            <input type="number" name="notas[{{ $item->id }}]" required min="0" max="10" step="0.5"
                                                   value="{{ old('notas.' . $item->id, $r?->nota) }}"
                                                   @if($jaAssinada) disabled @endif
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100">
                                        </div>
                                        <div class="md:col-span-3">
                                            <label class="block text-xs text-gray-500 mb-1">Comentário (opcional)</label>
                                            <input type="text" name="comentarios[{{ $item->id }}]" maxlength="1000"
                                                   value="{{ old('comentarios.' . $item->id, $r?->comentario) }}"
                                                   @if($jaAssinada) disabled @endif
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observações adicionais</label>
                    <textarea name="observacoes" rows="3" @if($jaAssinada) disabled @endif
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100">{{ old('observacoes', $avaliacao->observacoes) }}</textarea>
                </div>
            </div>

            @if (! $jaAssinada)
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex flex-wrap gap-3">
                        <button type="submit" name="assinar" value="0"
                                class="px-6 py-2.5 rounded-lg font-semibold text-sm bg-gray-100 hover:bg-gray-200 text-gray-800 transition">
                            💾 Salvar (sem assinar)
                        </button>

                        <button type="submit" name="assinar" value="1"
                                onclick="return confirm('Ao assinar esta avaliação NÃO poderá mais ser alterada. Confirma?');"
                                class="px-6 py-2.5 rounded-lg font-semibold text-sm bg-green-600 hover:bg-green-700 text-white transition">
                            ✍️ Salvar e Assinar
                        </button>
                    </div>
                </div>
            @endif
        </form>

        <div class="text-center text-xs text-gray-500 py-8">
            <p>SAD  / RS</p>
        </div>
    </div>
</body>
</html>
