<?php

namespace Tests\Unit;

use App\Services\FacebookProfileLookup;
use Illuminate\Support\Facades\Http;
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
        config()->set('services.facebook.approver_url', '');
        $profile = app(FacebookProfileLookup::class)->resolve('https://facebook.com/testuser');
        $this->assertSame('testuser', $profile['name']);
        $this->assertNull($profile['id']);
    }

    public function test_resolves_name_and_id_from_group_member_inspect(): void
    {
        config()->set('services.facebook.approver_url', 'http://approver.test');
        config()->set('services.facebook.lookup_http', false);
        Http::fake([
            'http://approver.test/lookup-profile' => Http::response([
                'ok' => true,
                'uid' => '100012345678901',
                'name' => 'Thảo Phương Sarah Wedding',
            ], 200),
        ]);

        $profile = app(FacebookProfileLookup::class)
            ->resolve('https://www.facebook.com/thaophuongsarahwedding');

        $this->assertSame('Thảo Phương Sarah Wedding', $profile['name']);
        $this->assertSame('100012345678901', $profile['id']);
    }
}
