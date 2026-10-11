<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliações em lote —</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: system-ui, -apple-system, "Segoe UI", sans-serif; }</style>
</head>
<body class="min-h-screen py-8 bg-gray-100 text-gray-900">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('brasao.png') }}" class="h-12" alt="Brasão">
                <div>
                    <h1 class="text-xl font-bold text-blue-800">SAD Campo Bom</h1>
                    <p class="text-xs text-gray-500">Múltiplas avaliações para o mesmo avaliador</p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h2 class="font-bold text-lg text-blue-900 mb-2">Antes de começar</h2>
            <p class="text-sm text-blue-800">
                Você vai avaliar <strong>{{ $lote->total }} servidor(es)</strong>.
                Informe seu nome completo e CPF uma única vez — depois só precisa preencher as notas.
            </p>
        </div>

        @if (session('erro'))
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">⚠️ {{ session('erro') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('avaliador.externo.lote.identificar', $token) }}"
              class="bg-white rounded-lg shadow p-6">
            @csrf

            <h2 class="text-lg font-bold mb-4">Identificação do Avaliador</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome completo *</label>
                    <input type="text" name="nome_avaliador" required maxlength="255"
                           value="{{ old('nome_avaliador') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CPF *</label>
                    <input type="text" name="cpf_avaliador" required maxlength="14" inputmode="numeric"
                           value="{{ old('cpf_avaliador') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="000.000.000-00">
                </div>
            </div>

            <button type="submit"
                    class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                Começar a avaliar
            </button>
        </form>
    </div>

    <script>
        document.querySelector('input[name="cpf_avaliador"]').addEventListener('input', function (e) {
            let v = e.target.value.replace(/\D/g, '').slice(0, 11);
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = v;
        });
    </script>
</body>
</html>
