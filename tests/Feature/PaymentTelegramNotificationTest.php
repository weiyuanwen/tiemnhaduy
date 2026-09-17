<?php

namespace Tests\Feature;

use App\Events\PaymentExpired;
use App\Events\PaymentSuccess;
use App\Mail\ServiceOrderPaidMail;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Services\Bank\PendingBankPoller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PaymentTelegramNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('services.telegram.bot_token', 'test-bot');
        config()->set('services.telegram.chat_id', '123');
    }

    public function test_paid_order_notifies_telegram_and_sends_mail(): void
    {
        Mail::fake();
        Http::fake([
            'https://api.telegram.org/*' => Http::response(['ok' => true], 200),
        ]);

        $order = ServiceOrder::factory()->create([
            'service_id' => Service::factory(),
            'status' => ServiceOrder::STATUS_PENDING,
            'order_code' => 'ORDFBABCDEFGH12',
            'amount' => 100000,
            'customer_email' => 'a@example.com',
            'facebook_profile_link' => 'https://facebook.com/beruby',
            'facebook_name' => 'Bé Ruby',
            'facebook_id' => '61577234078066',
            'expires_at' => now()->addMinutes(12),
        ]);

        app(\App\Services\OrderService::class)->confirmBankMatch($order->order_code, 'tx-mail');

        Mail::assertSent(ServiceOrderPaidMail::class, 1);
        Mail::assertSent(ServiceOrderPaidMail::class, function ($mail) {
            return $mail->hasTo('a@example.com');
        });
        $telegram = collect(Http::recorded())
            ->filter(fn ($pair) => str_contains($pair[0]->url(), 'api.telegram.org')
                && str_contains((string) $pair[0]['text'], 'thanh toán thành công'));
        $this->assertCount(1, $telegram);
        $this->assertStringContainsString('ORDFBABCDEFGH12', (string) $telegram->first()[0]['text']);
        $this->assertStringContainsString('https://facebook.com/beruby', (string) $telegram->first()[0]['text']);
        $this->assertStringContainsString(
            'https://web.facebook.com/groups/782860725537921/user/61577234078066/',
            (string) $telegram->first()[0]['text']
        );
    }

    public function test_expired_order_notifies_telegram(): void
    {
        Http::fake([
            'https://api.telegram.org/*' => Http::response(['ok' => true], 200),
        ]);

        $order = ServiceOrder::factory()->expired()->create([
            'order_code' => 'ORDFBZZZZZZZZZZ',
            'amount' => 100000,
        ]);

        event(new PaymentExpired($order));

        $telegram = collect(Http::recorded())
            ->filter(fn ($pair) => str_contains((string) $pair[0]['text'], 'hết hạn'));
        $this->assertCount(1, $telegram);
        $this->assertStringContainsString('ORDFBZZZZZZZZZZ', (string) $telegram->first()[0]['text']);
    }

    public function test_histbank_error_notifies_telegram_once(): void
    {
        Cache::flush();
        Http::fake([
            'http://histbank.test/transactions*' => Http::response('nope', 500),
            'https://api.telegram.org/*' => Http::response(['ok' => true], 200),
        ]);

        ServiceOrder::factory()->create([
            'service_id' => Service::factory(),
            'status' => ServiceOrder::STATUS_PENDING,
            'order_code' => 'ORDFBABCDEFGH12',
            'amount' => 100000,
            'expires_at' => now()->addMinutes(12),
            'created_at' => now()->subMinutes(1),
        ]);

        app(PendingBankPoller::class)->run();
        app(PendingBankPoller::class)->run(true);

        $telegram = collect(Http::recorded())
            ->filter(fn ($pair) => str_contains($pair[0]->url(), 'api.telegram.org'));
        $this->assertCount(1, $telegram);
        $text = (string) $telegram->first()[0]['text'];
        $this->assertStringContainsString('báo cáo đối soát CK', $text);
        $this->assertStringContainsString('HTTP 500', $text);
        $this->assertStringContainsString('Đối soát đêm 02:30', $text);
        $this->assertStringContainsString('đơn hết hạn trong 6 giờ', $text);
    }

    public function test_histbank_unreachable_report_uses_http_0(): void
    {
        Cache::flush();
        Http::fake([
            'http://histbank.test/transactions*' => function () {
                throw new \Illuminate\Http\Client\ConnectionException('Connection timed out');
            },
            'https://api.telegram.org/*' => Http::response(['ok' => true], 200),
        ]);
        config()->set('services.histbank.base_url', 'http://histbank.test');

        ServiceOrder::factory()->create([
            'service_id' => Service::factory(),
            'status' => ServiceOrder::STATUS_PENDING,
            'order_code' => 'ORDFBABCDEFGH12',
            'amount' => 100000,
            'expires_at' => now()->addMinutes(12),
            'created_at' => now()->subMinutes(1),
        ]);

        app(PendingBankPoller::class)->run();

        $telegram = collect(Http::recorded())
            ->filter(fn ($pair) => str_contains($pair[0]->url(), 'api.telegram.org'));
        $this->assertCount(1, $telegram);
        $text = (string) $telegram->first()[0]['text'];
        $this->assertStringContainsString('báo cáo đối soát CK', $text);
        $this->assertStringContainsString('HTTP 0', $text);
        $this->assertStringContainsString('Không kết nối được histbank', $text);
    }

    public function test_skips_telegram_without_credentials(): void
    {
        config()->set('services.telegram.bot_token', '');
        Http::fake();
        event(new PaymentSuccess(ServiceOrder::factory()->create([
            'status' => ServiceOrder::STATUS_PAID,
            'customer_email' => null,
        ])));
        Http::assertNothingSent();
    }
}
