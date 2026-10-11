<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar — SAD Campo Bom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: system-ui, -apple-system, "Segoe UI", sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">

            <div class="bg-white border-b border-gray-100 p-6 text-center">
                <img src="{{ asset('brasao.png') }}" class="h-20 mx-auto mb-3" alt="Brasão de Campo Bom">
                <h1 class="text-xl font-bold text-blue-800">SAD Campo Bom</h1>
                <p class="text-xs text-gray-500 mt-1">Sistema de Avaliação de Desempenho</p>
                <p class="text-[11px] text-gray-400 mt-0.5">Prefeitura Municipal de Campo Bom / RS</p>
            </div>

            <div class="p-6">

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                        @foreach ($errors->all() as $e)
                            <div>{{ $e }}</div>
                        @endforeach
                    </div>
                @endif

                @if (session('status'))
                    <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            E-mail
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               autocomplete="email"
                               placeholder="seu.email@campobom.rs.gov.br"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Senha
                        </label>
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            Manter conectado
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition shadow-sm">
                        Entrar
                    </button>
                </form>

                <div class="mt-5 pt-5 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Ao entrar, o sistema identifica seu <strong>papel</strong> e abre o painel correspondente.
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-6 text-xs text-gray-400">
            <p>Este acesso é restrito a servidores autorizados.</p>
            <p class="mt-1">Dúvidas ou problemas: procure a COMPAQ.</p>
        </div>

    </div>

</body>
</html>
