<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookGroupApproverClient
{
    /**
     * Best-effort: turn off per-member post approval. Never throws to callers.
     *
     * @return array{ok: bool, skipped?: bool, reason?: string, status?: int}
     */
    public function disablePostApproval(string $memberUrl, ?string $uid = null, ?string $name = null): array
    {
        $base = rtrim((string) config('services.facebook.approver_url'), '/');
        if ($base === '') {
            return ['ok' => true, 'skipped' => true, 'reason' => 'approver_unconfigured'];
        }

        $token = (string) config('services.facebook.approver_token');
        $timeout = (int) config('services.facebook.approver_timeout', 90);

        try {
            $request = Http::timeout($timeout)->acceptJson();
            if ($token !== '') {
                $request = $request->withToken($token);
            }

            $response = $request->post($base.'/disable-post-approval', [
                'member' => $memberUrl,
                'uid' => $uid,
                'name' => $name,
                'group_id' => config('services.facebook.group_id'),
            ]);

            $body = $response->json() ?? [];
            if ($response->successful() && ($body['ok'] ?? false)) {
                return [
                    'ok' => true,
                    'reason' => (string) ($body['reason'] ?? 'disabled'),
                    'status' => $response->status(),
                ];
            }

            return [
                'ok' => false,
                'reason' => (string) ($body['reason'] ?? ('http_'.$response->status())),
                'status' => $response->status(),
            ];
        } catch (\Throwable $e) {
            Log::warning('Facebook approver unreachable', ['error' => $e->getMessage()]);

            return ['ok' => false, 'reason' => 'unreachable'];
        }
    }
}
