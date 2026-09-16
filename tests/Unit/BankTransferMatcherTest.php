<?php

namespace Tests\Unit;

use App\Models\Service;
use App\Models\ServiceOrder;
use App\Services\Bank\BankTransferMatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BankTransferMatcherTest extends TestCase
{
    use RefreshDatabase;

    private function order(string $code, int $amount = 100000): ServiceOrder
    {
        return ServiceOrder::factory()->create([
            'order_code' => $code,
            'amount' => $amount,
            'status' => ServiceOrder::STATUS_PENDING,
            'service_id' => Service::factory(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $override
     * @return array<string, mixed>
     */
    private function tx(array $override): array
    {
        return array_merge([
            'id' => 'tx-1',
            'description' => 'ORDFBABCDEFGH12 ca phe',
            'amount' => 100000,
            'creditDebitIndicator' => 'CRDT',
        ], $override);
    }

    public function test_matches_credit_with_code_and_exact_amount(): void
    {
        $order = $this->order('ORDFBABCDEFGH12');
        $matches = (new BankTransferMatcher())->match([
            $this->tx([]),
        ], collect([$order]));

        $this->assertCount(1, $matches);
        $this->assertSame($order->id, $matches[0]['order']->id);
        $this->assertSame('tx-1', $matches[0]['txId']);
    }

    public function test_ignores_debit(): void
    {
        $order = $this->order('ORDFBABCDEFGH12');
        $matches = (new BankTransferMatcher())->match([
            $this->tx(['creditDebitIndicator' => 'DBIT']),
        ], collect([$order]));

        $this->assertSame([], $matches);
    }

    public function test_ignores_wrong_amount(): void
    {
        $order = $this->order('ORDFBABCDEFGH12', 100000);
        $matches = (new BankTransferMatcher())->match([
            $this->tx(['amount' => 99000]),
        ], collect([$order]));

        $this->assertSame([], $matches);
    }

    public function test_matches_code_glued_to_bank_prefix(): void
    {
        $order = $this->order('ORDFBABCDEFGH12');
        $matches = (new BankTransferMatcher())->match([
            $this->tx(['description' => 'IBFTORDFBABCDEFGH12 CT tu Nguyen Van A']),
        ], collect([$order]));

        $this->assertCount(1, $matches);
        $this->assertSame('tx-1', $matches[0]['txId']);
    }

    public function test_matches_code_glued_to_trailing_content(): void
    {
        $order = $this->order('ORDFBABCDEFGH12');
        $matches = (new BankTransferMatcher())->match([
            $this->tx(['description' => 'NDORDFBABCDEFGH12chuyentien']),
        ], collect([$order]));

        $this->assertCount(1, $matches);
    }
}
