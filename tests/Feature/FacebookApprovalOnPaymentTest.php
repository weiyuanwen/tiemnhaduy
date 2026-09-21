<?php

namespace Tests\Feature;

use App\Jobs\DisableFacebookPostApprovalJob;
use App\Jobs\LookupFacebookProfileJob;
use App\Mail\ServiceOrderPaidMail;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Services\FacebookProfileLookup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class FacebookApprovalOnPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order_looks_up_facebook_via_group_member_inspect(): void
    {
        Queue::fake();
        config()->set('services.facebook.approver_url', 'http://approver.test');
        config()->set('services.facebook.lookup_http', false);
        Http::fake([
            'http://approver.test/lookup-profile' => Http::response([
                'ok' => true,
                'uid' => '100012345678901',
                'name' => 'Thảo Phương Sarah Wedding',
                'reason' => 'ok',
                'membership' => 'member',
            ], 200),
        ]);

        $service = Service::factory()->create(['is_active' => true, 'price' => 100000]);
        $response = $this->postJson('/api/v1/orders', [
            'facebook_profile_link' => 'https://www.facebook.com/thaophuongsarahwedding',
            'service_id' => $service->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.facebook_name', 'thaophuongsarahwedding')
            ->assertJsonPath('data.facebook_id', null);
        Queue::assertPushed(LookupFacebookProfileJob::class, 1);
        Http::assertNotSent(fn ($request) => str_contains($request->url(), 'lookup-profile'));

        $order = ServiceOrder::query()->latest('id')->first();
        (new LookupFacebookProfileJob($order->id))->handle(app(FacebookProfileLookup::class));

        Http::assertSent(function ($request) {
            return $request->url() === 'http://approver.test/lookup-profile'
                && $request['url'] === 'https://www.facebook.com/thaophuongsarahwedding';
        });
        $this->assertDatabaseHas('service_orders', [
            'facebook_profile_link' => 'https://www.facebook.com/thaophuongsarahwedding',
            'facebook_name' => 'Thảo Phương Sarah Wedding',
            'facebook_id' => '100012345678901',
        ]);
    }

    public function test_create_order_looks_up_web_facebook_host_via_www_inspect(): void
    {
        Queue::fake();
        config()->set('services.facebook.approver_url', 'http://approver.test');
        config()->set('services.facebook.lookup_http', false);
        Http::fake([
            'http://approver.test/lookup-profile' => Http::response([
                'ok' => true,
                'uid' => '100000000000001',
                'name' => 'Edward Swim',
                'reason' => 'ok',
            ], 200),
        ]);

        $service = Service::factory()->create(['is_active' => true, 'price' => 100000]);
        $response = $this->postJson('/api/v1/orders', [
            'facebook_profile_link' => 'https://web.facebook.com/swimwedward',
            'service_id' => $service->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.facebook_name', 'swimwedward')
            ->assertJsonPath('data.facebook_id', null);
        Queue::assertPushed(LookupFacebookProfileJob::class);

        $order = ServiceOrder::query()->latest('id')->first();
        (new LookupFacebookProfileJob($order->id))->handle(app(FacebookProfileLookup::class));

        Http::assertSent(function ($request) {
            return $request->url() === 'http://approver.test/lookup-profile'
                && $request['url'] === 'https://www.facebook.com/swimwedward';
        });
        $this->assertDatabaseHas('service_orders', [
            'facebook_profile_link' => 'https://web.facebook.com/swimwedward',
            'facebook_name' => 'Edward Swim',
            'facebook_id' => '100000000000001',
        ]);
    }

    public function test_create_order_looks_up_facebook_name_from_html(): void
    {
        Queue::fake();
        config()->set('services.facebook.approver_url', '');
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
            ->assertJsonPath('data.facebook_name', 'beruby')
            ->assertJsonPath('data.facebook_id', null);

        $order = ServiceOrder::query()->latest('id')->first();
        (new LookupFacebookProfileJob($order->id))->handle(app(FacebookProfileLookup::class));

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
        Http::fake([
            'https://api.telegram.org/*' => Http::response(['ok' => true], 200),
        ]);
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
                && str_contains($html, 'Hệ thống đang tắt phê duyệt bài viết')
                && str_contains($html, 'Bé Ruby')
                && str_contains($html, '100014343376569')
                && str_contains($html, 'https://facebook.com/beruby')
                && str_contains($html, 'https://web.facebook.com/groups/782860725537921/user/100014343376569/');
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
            'https://api.telegram.org/*' => Http::response(['ok' => true], 200),
        ]);
        config()->set('services.facebook.approver_url', 'http://approver.test');
        config()->set('services.facebook.approver_token', 'secret');
        config()->set('services.telegram.bot_token', 'test-bot');
        config()->set('services.telegram.chat_id', '123');

        $order = ServiceOrder::factory()->create([
            'facebook_profile_link' => 'https://facebook.com/beruby',
            'facebook_name' => 'Bé Ruby',
            'facebook_id' => '100014343376569',
        ]);

        (new DisableFacebookPostApprovalJob($order->id))->handle(
            app(\App\Services\FacebookGroupApproverClient::class),
            app(FacebookProfileLookup::class),
            app(\App\Services\TelegramNotifier::class),
        );

        Http::assertSent(function ($request) {
            return $request->url() === 'http://approver.test/disable-post-approval'
                && $request['member'] === 'https://facebook.com/beruby'
                && $request['uid'] === '100014343376569';
        });
        $this->assertNotNull($order->fresh()->facebook_approval_disabled_at);
    }

    public function test_disable_job_looks_up_facebook_id_when_missing(): void
    {
        Http::fake([
            'http://approver.test/lookup-profile' => Http::response([
                'ok' => true,
                'uid' => '100014343376569',
                'name' => 'Bé Ruby',
            ], 200),
            'http://approver.test/disable-post-approval' => Http::response(['ok' => true, 'reason' => 'disabled'], 200),
            'https://api.telegram.org/*' => Http::response(['ok' => true], 200),
        ]);
        config()->set('services.facebook.approver_url', 'http://approver.test');
        config()->set('services.facebook.approver_token', 'secret');
        config()->set('services.facebook.lookup_http', false);
        config()->set('services.telegram.bot_token', '');

        $order = ServiceOrder::factory()->create([
            'facebook_profile_link' => 'https://facebook.com/beruby',
            'facebook_name' => 'beruby',
            'facebook_id' => null,
        ]);

        (new DisableFacebookPostApprovalJob($order->id))->handle(
            app(\App\Services\FacebookGroupApproverClient::class),
            app(FacebookProfileLookup::class),
            app(\App\Services\TelegramNotifier::class),
        );

        Http::assertSent(function ($request) {
            return $request->url() === 'http://approver.test/lookup-profile';
        });
        Http::assertSent(function ($request) {
            return $request->url() === 'http://approver.test/disable-post-approval'
                && $request['uid'] === '100014343376569';
        });
        $this->assertSame('100014343376569', $order->fresh()->facebook_id);
        $this->assertSame('Bé Ruby', $order->fresh()->facebook_name);
    }
}
