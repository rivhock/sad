<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muitas tentativas — SAD Campo Bom</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-white border-b border-gray-100 p-6 text-center">
            <img src="{{ asset('brasao.png') }}" class="h-20 mx-auto mb-3" alt="Brasão">
            <h1 class="text-xl font-bold text-blue-800">SAD Campo Bom</h1>
            <p class="text-xs text-gray-500 mt-1">Sistema de Avaliação de Desempenho</p>
        </div>

        <div class="p-6 text-center">
            <div class="text-5xl mb-4">⏱️</div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Muitas tentativas</h2>
            <p class="text-sm text-gray-600 mb-6">
                Por segurança, bloqueamos temporariamente novas tentativas.
                Aguarde alguns minutos e tente novamente.
            </p>

            <a href="/"
               class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                Voltar ao início
            </a>

            <p class="text-xs text-gray-400 mt-6">
                Se o problema persistir, procure a COMPAQ.
            </p>
        </div>
    </div>
</body>
</html>
