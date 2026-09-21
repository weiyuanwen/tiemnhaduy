<?php

namespace App\Support;

class SeoCanonical
{
    /**
     * Build the public canonical URL: apex host, HTTPS in production, homepage slash.
     */
    public static function url(?string $url = null): string
    {
        $fallback = rtrim((string) config('app.url'), '/') ?: 'https://tiemnhaduy.com';
        $app = parse_url($fallback) ?: [];
        $parsed = parse_url($url ?: $fallback) ?: [];

        $host = (string) ($app['host'] ?? $parsed['host'] ?? 'tiemnhaduy.com');
        $host = preg_replace('/^www\./i', '', $host) ?: $host;

        $path = (string) ($parsed['path'] ?? '/');
        if ($path === '' || $path === '/index.php') {
            $path = '/';
        } elseif ($path !== '/') {
            $path = rtrim($path, '/');
        }

        $scheme = app()->environment('production')
            ? 'https'
            : (string) ($app['scheme'] ?? $parsed['scheme'] ?? 'http');

        return $scheme.'://'.$host.$path;
    }
}
