<x-filament-panels::page>
    @if (! $this->podeEditar())
        <div class="p-4 rounded-lg bg-red-50 dark:bg-red-950 border border-red-300 dark:border-red-800">
            <div class="flex items-center gap-2">
                <span class="text-xl">🔒</span>
                <div>
                    <div class="font-bold text-red-900 dark:text-red-100">Bloqueada para edição</div>
                    <div class="text-sm text-red-800 dark:text-red-200">
                        Esta avaliação já foi assinada pelo servidor e não pode mais ser alterada.
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                <div class="text-sm text-gray-500">Nota: <strong>{{ number_format($record->nota_total, 2, ',', '.') }}</strong></div>
            @endif
        </div>
    </div>

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
                                <div class="mt-1 text-xs text-gray-500 italic">Comentário: {{ $r->comentario }}</div>
                            @endif
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <div class="text-2xl font-bold text-primary-600">{{ number_format($r->nota, 1, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500">Sem itens registrados.</div>
            @endforelse
        </div>
    </div>

    @if ($record->observacoes)
        <div class="mt-6 p-4 rounded-lg bg-yellow-50 dark:bg-yellow-950 border border-yellow-200 dark:border-yellow-800">
            <div class="text-sm font-semibold mb-1">Observações</div>
            <div class="text-sm">{{ $record->observacoes }}</div>
        </div>
    @endif

    @if ($record->qualificacoes->count())
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-3">🎓 Cursos enviados pelo servidor ({{ $record->qualificacoes->count() }})</h3>
            <div class="space-y-2">
                @foreach ($record->qualificacoes as $q)
                    <div class="p-3 rounded border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 flex items-start justify-between gap-3">
                        <div>
                            <div class="font-semibold text-sm">{{ $q->titulo }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $q->instituicao ?? '—' }} • {{ $q->carga_horaria }}h •
                                {{ $q->data_fim?->format('d/m/Y') }}
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($q->status === 'aprovado') bg-green-100 text-green-800
                            @elseif($q->status === 'rejeitado') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ $q->label_status }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-3">Assinaturas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php
                $tipos = ['chefia' => 'Chefia', 'avaliado' => 'Servidor', 'compaq' => 'COMPAQ'];
            @endphp
            @foreach ($tipos as $tipo => $label)
                @php $a = $record->assinaturas->firstWhere('tipo_assinante', $tipo); @endphp
                <div class="p-4 rounded-lg border {{ $a ? 'border-green-300 bg-green-50 dark:bg-green-950' : 'border-dashed border-gray-300 bg-gray-50 dark:bg-gray-900' }}">
                    <div class="text-xs text-gray-500 uppercase">{{ $label }}</div>
                    @if ($a)
                        <div class="font-semibold mt-1">{{ $a->user?->nome }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $a->assinado_em?->format('d/m/Y H:i') }}</div>
                    @else
                        <div class="text-sm text-gray-400 mt-1 italic">Aguardando</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
