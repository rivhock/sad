<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Verificação — SAD Campo Bom</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">SAD Campo Bom</h1>
            <p class="text-sm text-gray-500 mt-1">Sistema de Avaliação de Desempenho</p>
        </div>

        <div class="mb-6 p-4 rounded-lg bg-blue-50 border border-blue-200">
            <div class="text-xs uppercase text-blue-700 font-semibold mb-1">Verificação de identidade</div>
            <p class="text-sm text-blue-900">Informe seu <strong>CPF</strong> para acessar.</p>
        </div>

        @if (session('erro'))
            <div class="mb-4 p-3 rounded bg-red-50 border border-red-200 text-red-800 text-sm">⚠️ {{ session('erro') }}</div>
        @endif

        <form method="POST" action="{{ route('avaliacao.publica.verificar', $token) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                <input type="text" name="cpf" value="{{ old('cpf') }}" required maxlength="14" inputmode="numeric"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-lg tracking-wider"
                       placeholder="000.000.000-00">
            </div>
            <button type="submit" class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">Verificar e Acessar</button>
        </form>
    </div>
    <script>
        document.querySelector('input[name="cpf"]').addEventListener('input', function (e) {
            let v = e.target.value.replace(/\D/g, '').slice(0, 11);
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            e.target.value = v;
        });
    </script>
</body>
</html>
