<?php

namespace Tests\Feature;

use App\Jobs\DisableFacebookPostApprovalJob;
use App\Mail\ServiceOrderPaidMail;
use App\Models\Service;
use App\Models\ServiceOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class FacebookApprovalOnPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order_looks_up_facebook_name_from_html(): void
    {
        config()->set('services.facebook.lookup_http', true);
        Http::fake([
            'facebook.com/*' => Http::response(
                '<html><head><meta property="og:title" content="Bé Ruby | Facebook" /><meta property="al:android:url" content="fb://profile/100014343376569" /></head></html>',
                200
            ),
            'www.facebook.com/*' => Http::response(
                '<html><head><meta property="og:title" content="Bé Ruby | Facebook" /><meta property="al:android:url" content="fb://profile/100014343376569" /></head></html>',
                200
            ),
        ]);

        $service = Service::factory()->create(['is_active' => true, 'price' => 100000]);
        $response = $this->postJson('/api/v1/orders', [
            'facebook_profile_link' => 'https://facebook.com/beruby',
            'service_id' => $service->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.facebook_name', 'Bé Ruby')
            ->assertJsonPath('data.facebook_id', '100014343376569');
        $this->assertDatabaseHas('service_orders', [
            'facebook_profile_link' => 'https://facebook.com/beruby',
            'facebook_name' => 'Bé Ruby',
            'facebook_id' => '100014343376569',
        ]);
    }

    public function test_create_order_still_succeeds_when_facebook_lookup_fails(): void
    {
        config()->set('services.facebook.lookup_http', true);
        Http::fake([
            'facebook.com/*' => Http::response('nope', 500),
            'www.facebook.com/*' => Http::response('nope', 500),
        ]);

        $service = Service::factory()->create(['is_active' => true, 'price' => 100000]);
        $response = $this->postJson('/api/v1/orders', [
            'facebook_profile_link' => 'https://facebook.com/offlineuser',
            'service_id' => $service->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.facebook_name', 'offlineuser');
    }

    public function test_paid_mail_includes_facebook_and_approval_copy(): void
    {
        Mail::fake();
        Http::fake();
        config()->set('services.telegram.bot_token', 'test-bot');
        config()->set('services.telegram.chat_id', '123');

        $order = ServiceOrder::factory()->create([
            'service_id' => Service::factory(),
            'status' => ServiceOrder::STATUS_PENDING,
            'order_code' => 'ORDFBABCDEFGH12',
            'amount' => 100000,
            'customer_email' => 'a@example.com',
            'facebook_profile_link' => 'https://facebook.com/beruby',
            'facebook_name' => 'Bé Ruby',
            'facebook_id' => '100014343376569',
            'expires_at' => now()->addMinutes(12),
        ]);

        $result = app(\App\Services\OrderService::class)->confirmBankMatch($order->order_code, 'tx-fb');
        $this->assertTrue($result['success']);

        Mail::assertSent(ServiceOrderPaidMail::class, 1);
        Mail::assertSent(ServiceOrderPaidMail::class, function (ServiceOrderPaidMail $mail) {
            $html = $mail->render();

            return $mail->hasTo('a@example.com')
                && str_contains($html, 'Đã tắt phê duyệt bài viết thành công')
                && str_contains($html, 'Bé Ruby')
                && str_contains($html, '100014343376569')
                && str_contains($html, 'https://facebook.com/beruby');
        });
    }

    public function test_payment_succeeds_when_approver_fails(): void
    {
        Mail::fake();
        Http::fake([
            'http://approver.test/*' => Http::response(['ok' => false], 500),
        ]);
        config()->set('services.facebook.approver_url', 'http://approver.test');
        config()->set('services.telegram.bot_token', '');

        $order = ServiceOrder::factory()->create([
            'service_id' => Service::factory(),
            'status' => ServiceOrder::STATUS_PENDING,
            'order_code' => 'ORDFBFAILAPPROV',
            'facebook_profile_link' => 'https://facebook.com/beruby',
            'facebook_id' => '100014343376569',
            'expires_at' => now()->addMinutes(12),
        ]);

        $result = app(\App\Services\OrderService::class)->confirmBankMatch($order->order_code, 'tx-fail-approver');
        $this->assertTrue($result['success']);
        $this->assertSame(ServiceOrder::STATUS_PAID, $order->fresh()->status);
        $this->assertNull($order->fresh()->facebook_approval_disabled_at);
    }

    public function test_disable_job_calls_approver_when_configured(): void
    {
        Http::fake([
            'http://approver.test/disable-post-approval' => Http::response(['ok' => true, 'reason' => 'disabled'], 200),
        ]);
        config()->set('services.facebook.approver_url', 'http://approver.test');
        config()->set('services.facebook.approver_token', 'secret');

        $order = ServiceOrder::factory()->create([
            'facebook_profile_link' => 'https://facebook.com/beruby',
            'facebook_name' => 'Bé Ruby',
            'facebook_id' => '100014343376569',
        ]);

        (new DisableFacebookPostApprovalJob($order->id))->handle(app(\App\Services\FacebookGroupApproverClient::class));

        Http::assertSent(function ($request) {
            return $request->url() === 'http://approver.test/disable-post-approval'
                && $request['member'] === 'https://facebook.com/beruby'
                && $request['uid'] === '100014343376569';
        });
        $this->assertNotNull($order->fresh()->facebook_approval_disabled_at);
    }
}
