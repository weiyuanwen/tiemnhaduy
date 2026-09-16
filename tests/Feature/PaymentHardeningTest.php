<?php

namespace Tests\Feature;

use App\Mail\ServiceOrderPaidMail;
use App\Models\BankTransaction;
use App\Models\Service;
use App\Models\ServiceOrder;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PaymentHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_orders_from_the_same_ip_is_rate_limited(): void
    {
        $service = Service::factory()->create([
            'is_active' => true,
            'price' => 150000,
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/orders', [
                'facebook_profile_link' => 'https://facebook.com/payer'.$i,
                'service_id' => $service->id,
            ], ['REMOTE_ADDR' => '203.0.113.10'])->assertStatus(201);
        }

        $this->postJson('/api/v1/orders', [
            'facebook_profile_link' => 'https://facebook.com/payer-spam',
            'service_id' => $service->id,
        ], ['REMOTE_ADDR' => '203.0.113.10'])
            ->assertStatus(429)
            ->assertJsonPath('status', false);
    }

    public function test_paid_match_stores_bank_transaction_history(): void
    {
        Mail::fake();

        $order = ServiceOrder::factory()->create([
            'service_id' => Service::factory(),
            'status' => ServiceOrder::STATUS_PENDING,
            'order_code' => 'ORDFBABCDEFGH12',
            'amount' => 150000,
            'customer_email' => 'payer@example.com',
            'expires_at' => now()->addMinutes(12),
        ]);

        $result = app(\App\Services\OrderService::class)->confirmBankMatch(
            $order->order_code,
            'tx-150k-1',
            false,
            [
                'amount' => 150000,
                'description' => 'CK ORDFBABCDEFGH12 PHI NHOM',
            ]
        );

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('bank_transactions', [
            'service_order_id' => $order->id,
            'bank_txn_id' => 'tx-150k-1',
            'amount' => 150000,
        ]);
        $this->assertSame(1, BankTransaction::query()->count());
    }

    public function test_paid_mail_uses_branded_html(): void
    {
        $order = ServiceOrder::factory()->paid()->create([
            'order_code' => 'ORDFBMAILTEST01',
            'amount' => 150000,
            'facebook_name' => 'Lan Chu Se',
            'customer_email' => 'lan@example.com',
        ]);

        $html = (new ServiceOrderPaidMail($order))->render();

        $this->assertStringContainsString('Thanh toán thành công', $html);
        $this->assertStringContainsString('ORDFBMAILTEST01', $html);
        $this->assertStringContainsString('150.000', $html);
        $this->assertStringContainsString('phê duyệt bài viết', $html);
        $this->assertStringContainsString('Ăn vặt Chư Sê', $html);
    }

    public function test_default_group_fee_is_one_hundred_fifty_thousand(): void
    {
        $this->seed(ServiceSeeder::class);

        $this->assertDatabaseHas('services', [
            'id' => 1,
            'price' => 150000,
            'is_active' => true,
        ]);
    }

    public function test_debug_dump_is_hidden_in_production(): void
    {
        $this->app['env'] = 'production';
        config(['app.env' => 'production']);

        $this->getJson('/api/v1/debug/data')->assertNotFound();
    }
}
