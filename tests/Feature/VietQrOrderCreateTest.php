<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class VietQrOrderCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order_returns_vietqr_and_accepts_optional_email(): void
    {
        Event::fake();
        config()->set('services.vietqr.bank_id', 'TPB');
        config()->set('services.vietqr.account_no', '03738073001');
        config()->set('services.vietqr.account_name', 'TIEM NHA DUY');
        config()->set('services.payment.window_minutes', 12);

        $service = Service::factory()->create(['is_active' => true, 'price' => 100000]);

        $response = $this->postJson('/api/v1/orders', [
            'facebook_profile_link' => 'https://facebook.com/testuser',
            'service_id' => $service->id,
            'email' => 'a@example.com',
        ]);

        $response->assertStatus(201);
        $code = $response->json('data.order_code');
        $this->assertMatchesRegularExpression('/^ORDFB[A-Z0-9]{10}$/', $code);
        $this->assertSame($code, $response->json('data.transfer_content'));
        $this->assertStringContainsString('img.vietqr.io', $response->json('data.qr_image_url'));
        $this->assertStringContainsString($code, $response->json('data.qr_image_url'));
        $this->assertDatabaseHas('service_orders', [
            'order_code' => $code,
            'customer_email' => 'a@example.com',
        ]);
    }
}
