<?php

namespace Tests\Feature;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Tests\TestCase;

class SeoCanonicalTest extends TestCase
{
    public function test_homepage_canonical_uses_trailing_slash(): void
    {
        config(['app.url' => 'https://tiemnhaduy.com']);
        url()->forceRootUrl('https://tiemnhaduy.com');

        $this->get('/')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="https://tiemnhaduy.com/">', false);
    }

    public function test_www_host_redirects_to_apex(): void
    {
        $response = $this->call('GET', 'http://www.tiemnhaduy.com/ve-chung-toi');

        $response->assertStatus(301);
        $this->assertSame('http://tiemnhaduy.com/ve-chung-toi', $response->headers->get('Location'));
    }

    public function test_trailing_slash_redirects_except_home(): void
    {
        $request = Request::create('http://tiemnhaduy.test/ve-chung-toi/', 'GET');
        $response = $this->app->make(Kernel::class)->handle($request);

        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame('http://tiemnhaduy.test/ve-chung-toi', $response->headers->get('Location'));
    }

    public function test_index_php_redirects_home(): void
    {
        $request = Request::create('http://tiemnhaduy.test/index.php', 'GET');
        $response = $this->app->make(Kernel::class)->handle($request);

        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame('http://tiemnhaduy.test/', $response->headers->get('Location'));
    }

    public function test_search_placeholder_permanent_redirects_home(): void
    {
        $this->get('/search')
            ->assertStatus(301)
            ->assertRedirect('/');
    }

    public function test_cart_placeholder_permanent_redirects_home(): void
    {
        $this->get('/cart')
            ->assertStatus(301);
    }

    public function test_offline_page_is_ok_and_noindex(): void
    {
        $this->get('/offline')
            ->assertOk()
            ->assertSee('noindex', false);
    }
}
