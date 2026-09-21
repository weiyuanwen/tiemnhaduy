<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanonicalHost
{
    /**
     * Collapse www, index.php, and trailing slashes onto one public URL.
     *
     * HTTPS is applied on the redirect target in production. Scheme-only
     * redirects are skipped so TLS termination in front of nginx does not loop.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        if ($this->shouldSkipPath($request)) {
            return $next($request);
        }

        $host = strtolower($request->getHost());
        $targetHost = str_starts_with($host, 'www.') ? substr($host, 4) : $host;
        $path = $this->requestPath($request);
        $targetPath = $this->canonicalPath($path);

        if ($targetHost === $host && $targetPath === $path) {
            return $next($request);
        }

        $scheme = app()->environment('production') ? 'https' : $request->getScheme();
        $url = $scheme.'://'.$targetHost.$targetPath;
        $query = $request->getQueryString();
        if ($query) {
            $url .= '?'.$query;
        }

        return redirect()->away($url, 301);
    }

    private function requestPath(Request $request): string
    {
        $path = parse_url($request->getRequestUri(), PHP_URL_PATH);
        if (! is_string($path) || $path === '') {
            $path = $request->getPathInfo() ?: '/';
        }

        return $path;
    }

    private function canonicalPath(string $path): string
    {
        if ($path === '/index.php' || str_ends_with($path, '/index.php')) {
            return '/';
        }

        if ($path !== '/' && str_ends_with($path, '/')) {
            return rtrim($path, '/');
        }

        return $path;
    }

    private function shouldSkipPath(Request $request): bool
    {
        return $request->is(
            'app',
            'app/*',
            'admin',
            'admin/*',
            'api',
            'api/*',
            'livewire/*',
            'filament/*',
            'up'
        );
    }
}
