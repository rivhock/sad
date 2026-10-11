<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        if (Auth::check()) {
            return redirect($this->destinoPorPapel(Auth::user()));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Informe o e-mail.',
            'email.email'       => 'E-mail inválido.',
            'password.required' => 'Informe a senha.',
        ]);

        // Verifica rate limit
        $key = \Illuminate\Support\Str::transliterate(
            \Illuminate\Support\Str::lower($request->input('email', '')) . '|' . $request->ip()
        );

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 5)) {
            $segundos = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
            $minutos = ceil($segundos / 60);

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => "Muitas tentativas de login. Aguarde {$minutos} minuto(s) e tente novamente."
                ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            \Illuminate\Support\Facades\RateLimiter::hit($key, 60);
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'E-mail ou senha incorretos.']);
        }

        $user = Auth::user();

        if (! $user->ativo) {
            Auth::logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Esta conta está inativa. Procure a COMPAQ.']);
        }

        $destino = $this->destinoPorPapel($user);

        if (! $destino) {
            Auth::logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Sua conta não tem permissão para acessar o sistema.']);
        }

        \Illuminate\Support\Facades\RateLimiter::clear($key);
        $request->session()->regenerate();

        return redirect($destino);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function destinoPorPapel($user): ?string
    {
        if ($user->hasAnyRole(['Administrador', 'RH'])) {
            return '/admin';
        }
        if ($user->hasRole('COMPAQ')) {
            return '/compaq';
        }
        if ($user->hasRole('Avaliador')) {
            return '/avaliador';
        }
        return null;
    }
}
