<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliações — {{ $nomeChefia }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: system-ui, -apple-system, "Segoe UI", sans-serif; }</style>
</head>
<body class="min-h-screen py-8 bg-gray-100 text-gray-900">
    <div class="max-w-4xl mx-auto px-4">

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('brasao.png') }}" class="h-12" alt="Brasão">
                    <div>
                        <h1 class="text-xl font-bold text-blue-800">SAD Campo Bom</h1>
                        <p class="text-xs text-gray-500">Avaliações a preencher</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-xs uppercase text-gray-500">Avaliador</div>
                    <div class="font-semibold">{{ $nomeChefia }}</div>
                    <div class="text-xs text-gray-500">CPF: {{ $cpfChefia }}</div>
                </div>
            </div>
        </div>

        @if (session('sucesso'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">✅ {{ session('sucesso') }}</div>
        @endif
        @if (session('erro'))
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">⚠️ {{ session('erro') }}</div>
        @endif

        @php
            $total = $avaliacoes->count();
            $assinadas = $avaliacoes->filter(fn($a) => $a->assinadaPelaChefia())->count();
            $pendentes = $total - $assinadas;
        @endphp

        <div class="grid grid-cols-3 gap-3 mb-6">
            <div class="p-4 rounded-lg bg-blue-50 border border-blue-200">
                <div class="text-xs uppercase text-blue-700 font-semibold">Total</div>
                <div class="text-3xl font-bold text-blue-900">{{ $total }}</div>
            </div>
            <div class="p-4 rounded-lg bg-green-50 border border-green-200">
                <div class="text-xs uppercase text-green-700 font-semibold">Assinadas</div>
                <div class="text-3xl font-bold text-green-900">{{ $assinadas }}</div>
            </div>
            <div class="p-4 rounded-lg bg-yellow-50 border border-yellow-200">
                <div class="text-xs uppercase text-yellow-700 font-semibold">Pendentes</div>
                <div class="text-3xl font-bold text-yellow-900">{{ $pendentes }}</div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold mb-4">Servidores para avaliar</h2>

            <div class="space-y-3">
                @foreach ($avaliacoes as $av)
                    @php $assinada = $av->assinadaPelaChefia(); @endphp
                    <a href="{{ route('avaliador.externo.lote.avaliacao.show', [$token, $av->id]) }}"
                       class="block p-4 rounded-lg border-2 {{ $assinada ? 'border-green-300 bg-green-50' : 'border-gray-200 bg-gray-50 hover:border-blue-300 hover:bg-blue-50' }} transition">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900">
                                    {{ $av->servidor?->user?->nome }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    Mat. {{ $av->servidor?->user?->matricula }} •
                                    {{ $av->servidor?->cargo?->nome ?? '—' }} •
                                    {{ $av->servidor?->lotacao?->nome ?? '—' }}
                                </div>
                                @if ($av->nota_total !== null)
                                    <div class="text-xs text-gray-500 mt-1">
                                        Nota atual: <strong>{{ number_format($av->nota_total, 2, ',', '.') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <div class="text-right flex-shrink-0">
                                @if ($assinada)
                                    <div class="px-3 py-1 rounded-full bg-green-600 text-white text-xs font-semibold">✅ Assinada</div>
                                @elseif ($av->respostas->count() > 0)
                                    <div class="px-3 py-1 rounded-full bg-yellow-500 text-white text-xs font-semibold">✏️ Em andamento</div>
                                @else
                                    <div class="px-3 py-1 rounded-full bg-gray-400 text-white text-xs font-semibold">⏳ Pendente</div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($assinadas === $total && $total > 0)
                <div class="mt-6 p-4 rounded-lg bg-green-50 border border-green-200 text-center">
                    <div class="text-2xl mb-2">🎉</div>
                    <div class="font-bold text-green-900">Tudo pronto!</div>
                    <div class="text-sm text-green-800">Todas as avaliações foram preenchidas e assinadas.</div>
                </div>
            @endif
        </div>

        <div class="text-center text-xs text-gray-500 py-8">
            <p>SAD Campo Bom — Prefeitura Municipal de Campo Bom / RS</p>
        </div>
    </div>
</body>
</html>
