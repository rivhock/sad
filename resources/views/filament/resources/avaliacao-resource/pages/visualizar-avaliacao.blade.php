<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900">
            <div class="text-xs text-gray-500 uppercase">Servidor</div>
            <div class="font-semibold">{{ $record->servidor?->user?->nome }}</div>
            <div class="text-sm text-gray-500">Mat. {{ $record->servidor?->user?->matricula }}</div>
        </div>
        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900">
            <div class="text-xs text-gray-500 uppercase">Ciclo / Formulário</div>
            <div class="font-semibold">{{ $record->ciclo?->nome }}</div>
            <div class="text-sm text-gray-500">{{ $record->formulario?->titulo }}</div>
        </div>
        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900">
            <div class="text-xs text-gray-500 uppercase">Status</div>
            <div class="font-semibold">{{ $record->status?->label() }}</div>
            @if ($record->nota_total !== null)
                <div class="text-sm text-gray-500">Nota total: <strong>{{ number_format($record->nota_total, 2, ',', '.') }}</strong></div>
            @endif
        </div>
    </div>

    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-3">Itens Avaliados</h3>
        @php $respostas = $record->respostas->keyBy('item_id'); @endphp

        <div class="space-y-2">
            @foreach ($record->formulario->itens as $item)
                @php $r = $respostas->get($item->id); @endphp
                <div class="p-3 rounded border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-8 text-center font-bold text-gray-500">{{ $item->ordem }}</div>
                        <div class="flex-1">
                            <div class="text-sm">{{ $item->descricao }}</div>
                            @if ($r?->comentario)
                                <div class="mt-1 text-xs text-gray-500 italic">Comentário: {{ $r->comentario }}</div>
                            @endif
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <div class="text-2xl font-bold text-primary-600">{{ $r ? number_format($r->nota, 1, ',', '.') : '—' }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if ($record->observacoes)
        <div class="mt-6 p-4 rounded-lg bg-yellow-50 dark:bg-yellow-950 border border-yellow-200 dark:border-yellow-800">
            <div class="text-sm font-semibold mb-1">Observações adicionais</div>
            <div class="text-sm">{{ $record->observacoes }}</div>
        </div>
    @endif

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
                <div class="p-4 rounded-lg border {{ $a ? 'border-green-300 bg-green-50 dark:bg-green-950' : 'border-dashed border-gray-300 bg-gray-50 dark:bg-gray-900' }}">
                    <div class="text-xs text-gray-500 uppercase">{{ $label }}</div>
                    @if ($a)
                        <div class="font-semibold mt-1">{{ $a->user?->nome }}</div>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $a->assinado_em?->format('d/m/Y H:i') }}<br>
                            IP: {{ $a->ip }}<br>
                            Hash: <code class="text-[10px]">{{ substr($a->hash, 0, 16) }}…</code>
                        </div>
                    @else
                        <div class="text-sm text-gray-400 mt-1 italic">Aguardando assinatura</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
