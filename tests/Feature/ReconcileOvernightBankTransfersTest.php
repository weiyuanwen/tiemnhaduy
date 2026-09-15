<?php

namespace Tests\Feature;

use App\Events\PaymentSuccess;
use App\Models\Service;
use App\Models\ServiceOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ReconcileOvernightBankTransfersTest extends TestCase
{
    use RefreshDatabase;

    public function test_reconcile_pays_expired_order_when_transfer_matches(): void
    {
        Event::fake([PaymentSuccess::class]);
        config()->set('services.histbank.base_url', 'http://histbank.test');

        $order = ServiceOrder::factory()->create([
            'service_id' => Service::factory(),
            'status' => ServiceOrder::STATUS_EXPIRED,
            'order_code' => 'ORDFBABCDEFGH12',
            'amount' => 100000,
            'expires_at' => now()->subMinutes(10),
            'paid_at' => null,
            'created_at' => now()->subMinutes(20),
        ]);

        Http::fake([
            'http://histbank.test/transactions*' => Http::response([
                'count' => 1,
                'transactions' => [[
                    'id' => 'tx-late-1',
                    'description' => 'CK ORDFBABCDEFGH12',
                    'amount' => 100000,
                    'creditDebitIndicator' => 'CRDT',
                ]],
            ], 200),
        ]);

        $this->artisan('bank:reconcile-overnight')->assertSuccessful();

        $this->assertSame(ServiceOrder::STATUS_PAID, $order->fresh()->status);
        Event::assertDispatched(PaymentSuccess::class);
    }
}
