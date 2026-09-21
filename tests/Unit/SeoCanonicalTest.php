<?php

namespace Tests\Unit;

use App\Support\SeoCanonical;
use Tests\TestCase;

class SeoCanonicalTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['app.url' => 'https://tiemnhaduy.com']);
    }

    public function test_homepage_keeps_trailing_slash(): void
    {
        $this->assertSame('https://tiemnhaduy.com/', SeoCanonical::url('https://tiemnhaduy.com'));
        $this->assertSame('https://tiemnhaduy.com/', SeoCanonical::url('https://www.tiemnhaduy.com/'));
        $this->assertSame('https://tiemnhaduy.com/', SeoCanonical::url('https://tiemnhaduy.com/index.php'));
    }

    public function test_inner_pages_drop_trailing_slash_and_www(): void
    {
        $this->assertSame(
            'https://tiemnhaduy.com/ve-chung-toi',
            SeoCanonical::url('https://www.tiemnhaduy.com/ve-chung-toi/')
        );
    }
}
