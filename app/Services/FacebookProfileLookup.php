<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookProfileLookup
{
    private const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

    public function __construct(private FacebookGroupApproverClient $approver)
    {
    }

    /**
     * @return array{name: string|null, id: string|null, url: string}
     */
    public function resolve(string $profileUrl): array
    {
        $url = trim($profileUrl);
        $id = $this->idFromUrl($url);
        $name = $this->nameFromUrl($url);

        $inspected = $this->approver->lookupProfile($this->canonicalUrl($url));
        if (($inspected['ok'] ?? false) && ! ($inspected['skipped'] ?? false)) {
            return [
                'name' => $inspected['name'] ?: $name,
                'id' => $inspected['id'] ?: $id,
                'url' => $url,
            ];
        }

        if (! (bool) config('services.facebook.lookup_http', true)) {
            return ['name' => $name, 'id' => $id, 'url' => $url];
        }

        try {
            $html = $this->fetchHtml($this->canonicalUrl($url));
            if (is_string($html) && $html !== '') {
                $id = $this->idFromHtml($html) ?? $id;
                $name = $this->nameFromHtml($html) ?? $name;
            }
        } catch (\Throwable $e) {
            Log::info('Facebook profile lookup skipped', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
        }

        return ['name' => $name, 'id' => $id, 'url' => $url];
    }

    public function nameFromHtml(string $html): ?string
    {
        if (preg_match('/<meta[^>]+property=["\']og:title["\'][^>]+content=["\']([^"\']+)/i', $html, $matches)) {
            $name = $this->cleanDisplayName(html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            if ($name) {
                return $name;
            }
        }

        if (preg_match('/<title>([^<]+)<\/title>/i', $html, $matches)) {
            $name = $this->cleanDisplayName(html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            if ($name) {
                return $name;
            }
        }

        return null;
    }

    public function idFromHtml(string $html): ?string
    {
        return app(FacebookUidExtractorService::class)->extractUidFromHtml($html);
    }

    public function idFromUrl(string $url): ?string
    {
        if (preg_match('/[?&]id=(\d{6,})/i', $url, $matches)) {
            return $matches[1];
        }

        return app(FacebookUidExtractorService::class)->extractUidFromUrl($url);
    }

    public function canonicalUrl(string $url): string
    {
        $canonical = preg_replace(
            '~^https?://(?:web|m|mbasic)\.facebook\.com~i',
            'https://www.facebook.com',
            trim($url)
        ) ?? trim($url);

        return preg_replace('~^https?://facebook\.com~i', 'https://www.facebook.com', $canonical) ?? $canonical;
    }

    public function nameFromUrl(string $url): ?string
    {
        if (preg_match('/facebook\.com\/profile\.php\?id=(\d+)/i', $url, $matches)) {
            return 'ID '.$matches[1];
        }

        if (preg_match('~facebook\\.com/([^/?]+)~i', $url, $matches)) {
            $slug = rawurldecode($matches[1]);
            $reserved = ['profile.php', 'people', 'groups', 'pages', 'watch', 'reel', 'share', 'photo', 'posts'];
            if ($slug !== '' && ! in_array(strtolower($slug), $reserved, true) && ! preg_match('/^\d+$/', $slug)) {
                return $slug;
            }
            if (preg_match('/^\d{6,}$/', $slug)) {
                return 'ID '.$slug;
            }
        }

        return null;
    }

    private function cleanDisplayName(string $raw): ?string
    {
        $name = trim(preg_replace('/\s+/', ' ', $raw) ?? '');
        $name = preg_replace('/\s*[|\-–—]\s*Facebook.*$/i', '', $name) ?? $name;
        $name = trim($name);
        $blocked = ['facebook', 'log in', 'sign up', 'đăng nhập', 'đăng ký'];
        if ($name === '' || in_array(mb_strtolower($name), $blocked, true)) {
            return null;
        }

        return $name;
    }

    private function fetchHtml(string $url): ?string
    {
        $timeout = (int) config('services.facebook.lookup_timeout', 8);
        $response = Http::withHeaders([
            'User-Agent' => self::USER_AGENT,
            'Accept' => 'text/html,application/xhtml+xml',
            'Accept-Language' => 'vi-VN,vi;q=0.9,en;q=0.8',
        ])
            ->timeout($timeout)
            ->connectTimeout(4)
            ->get($url);

        if (! $response->successful()) {
            return null;
        }

        return $response->body();
    }
}
