<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjetarCssSpinner
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $contentType = $response->headers->get('Content-Type', '');
        if (! str_contains($contentType, 'text/html')) {
            return $response;
        }

        $html = $response->getContent();

        if (is_string($html) && str_contains($html, '</head>') && ! str_contains($html, 'sem-spinner.css')) {
            $tag = '<link rel="stylesheet" href="' . asset('css/sem-spinner.css') . '">';
            $html = str_replace('</head>', $tag . '</head>', $html);
            $response->setContent($html);
        }

        return $response;
    }
}
