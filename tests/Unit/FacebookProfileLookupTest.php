<?php

namespace Tests\Unit;

use App\Services\FacebookProfileLookup;
use Tests\TestCase;

class FacebookProfileLookupTest extends TestCase
{
    public function test_reads_name_from_og_title_and_strips_facebook_suffix(): void
    {
        $html = '<html><head><meta property="og:title" content="Bé Ruby | Facebook"><title>Login</title></head></html>';
        $name = app(FacebookProfileLookup::class)->nameFromHtml($html);
        $this->assertSame('Bé Ruby', $name);
    }

    public function test_falls_back_to_username_in_url(): void
    {
        $lookup = app(FacebookProfileLookup::class);
        $this->assertSame('nguyenvana', $lookup->nameFromUrl('https://www.facebook.com/nguyenvana'));
        $this->assertSame('ID 100014343376569', $lookup->nameFromUrl('https://facebook.com/profile.php?id=100014343376569'));
    }

    public function test_resolves_username_without_http_when_lookup_disabled(): void
    {
        config()->set('services.facebook.lookup_http', false);
        $profile = app(FacebookProfileLookup::class)->resolve('https://facebook.com/testuser');
        $this->assertSame('testuser', $profile['name']);
        $this->assertNull($profile['id']);
    }
}
