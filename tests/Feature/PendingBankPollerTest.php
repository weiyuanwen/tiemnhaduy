<?php

namespace Tests\Feature;

use App\Events\PaymentSuccess;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Services\Bank\PendingBankPoller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PendingBankPollerTest extends TestCase
{
    use RefreshDatabase;

    public function test_idle_does_not_call_histbank(): void
    {
        Http::fake();
        $result = app(PendingBankPoller::class)->run();
        $this->assertFalse($result['fetched']);
        $this->assertSame('idle', $result['reason']);
        Http::assertNothingSent();
    }

    public function test_skips_fetch_during_initial_delay(): void
    {
        Http::fake();
        ServiceOrder::factory()->create([
            'service_id' => Service::factory(),
            'status' => ServiceOrder::STATUS_PENDING,
            'order_code' => 'ORDFBABCDEFGH12',
            'amount' => 100000,
            'expires_at' => now()->addMinutes(12),
            'created_at' => now(),
        ]);

        $result = app(PendingBankPoller::class)->run();
        $this->assertFalse($result['fetched']);
        $this->assertSame('initial_delay', $result['reason']);
        Http::assertNothingSent();
    }

    public function test_matches_and_marks_paid(): void
    {
        Event::fake([PaymentSuccess::class]);
        Cache::flush();
        config()->set('services.histbank.base_url', 'http://histbank.test');

        $order = ServiceOrder::factory()->create([
            'service_id' => Service::factory(),
            'status' => ServiceOrder::STATUS_PENDING,
            'order_code' => 'ORDFBABCDEFGH12',
            'amount' => 100000,
            'expires_at' => now()->addMinutes(12),
            'created_at' => now()->subMinutes(1),
        ]);

        Http::fake([
            'http://histbank.test/transactions*' => Http::response([
                'count' => 1,
                'transactions' => [[
                    'id' => 'tx-live-1',
                    'description' => 'CK ORDFBABCDEFGH12',
                    'amount' => 100000,
                    'creditDebitIndicator' => 'CRDT',
                ]],
            ], 200),
        ]);

        $result = app(PendingBankPoller::class)->run();

        $this->assertTrue($result['fetched']);
        $this->assertSame(1, $result['matched']);
        $this->assertSame(ServiceOrder::STATUS_PAID, $order->fresh()->status);
        Event::assertDispatched(PaymentSuccess::class);
    }
}
