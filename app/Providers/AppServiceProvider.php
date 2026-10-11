<?php

namespace App\Providers;

use App\Models\Avaliacao;
use App\Observers\AvaliacaoObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Rate limiter para login: 5 tentativas por minuto por e-mail+IP
        RateLimiter::for('login', function (Request $request) {
            $key = Str::transliterate(Str::lower($request->input('email', '')) . '|' . $request->ip());

            return Limit::perMinute(5)
                ->by($key)
                ->response(function (Request $request, array $headers) {
                    $segundos = $headers['Retry-After'] ?? 60;
                    $minutos = ceil($segundos / 60);

                    return back()
                        ->withInput($request->only('email'))
                        ->withErrors([
                            'email' => "Muitas tentativas de login. Por segurança, aguarde {$minutos} minuto(s) e tente novamente."
                        ]);
                });
        });

        // Força HTTPS quando estiver em produção (atrás do proxy reverso)
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Avaliacao::observe(AvaliacaoObserver::class);
        //
    }
}
