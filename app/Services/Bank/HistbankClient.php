<?php

namespace App\Services\Bank;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HistbankClient
{
    /**
     * @return array{ok: bool, transactions: array<int, array<string, mixed>>, status: int}
     */
    public function transactions(int $days = 1, int $pageSize = 100): array
    {
        $base = rtrim((string) config('services.histbank.base_url'), '/');
        $timeout = (int) config('services.histbank.timeout', 15);

        try {
            $response = Http::timeout($timeout)
                ->acceptJson()
                ->get($base.'/transactions', [
                    'days' => $days,
                    'pageSize' => $pageSize,
                    'pageNumber' => 1,
                ]);
        } catch (ConnectionException $e) {
            Log::warning('histbank unreachable', ['error' => $e->getMessage()]);

            return ['ok' => false, 'transactions' => [], 'status' => 0];
        }

        if (! $response->successful()) {
            Log::warning('histbank non-2xx', ['status' => $response->status()]);

            return ['ok' => false, 'transactions' => [], 'status' => $response->status()];
        }

        $json = $response->json();

        return [
            'ok' => true,
            'transactions' => $json['transactions'] ?? [],
            'status' => $response->status(),
        ];
    }
}
