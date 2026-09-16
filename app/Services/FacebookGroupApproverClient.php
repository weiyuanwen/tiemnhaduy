<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookGroupApproverClient
{
    /**
     * Inspect a Facebook URL with the logged-in group-admin session.
     *
     * @return array{ok: bool, skipped?: bool, name?: string|null, id?: string|null, reason?: string, status?: int}
     */
    public function lookupProfile(string $profileUrl): array
    {
        $base = rtrim((string) config('services.facebook.approver_url'), '/');
        if ($base === '') {
            return ['ok' => false, 'skipped' => true, 'reason' => 'approver_unconfigured'];
        }

        try {
            $response = $this->request()->post($base.'/lookup-profile', [
                'url' => $profileUrl,
                'group_id' => config('services.facebook.group_id'),
            ]);

            $body = $response->json() ?? [];
            $name = isset($body['name']) ? trim((string) $body['name']) : '';
            $id = isset($body['uid']) ? trim((string) $body['uid']) : '';
            if ($id === '' && isset($body['id'])) {
                $id = trim((string) $body['id']);
            }

            if ($response->successful() && ($body['ok'] ?? false) && ($name !== '' || $id !== '')) {
                return [
                    'ok' => true,
                    'name' => $name !== '' ? $name : null,
                    'id' => $id !== '' ? $id : null,
                    'reason' => (string) ($body['reason'] ?? 'ok'),
                    'status' => $response->status(),
                ];
            }

            return [
                'ok' => false,
                'reason' => (string) ($body['reason'] ?? ('http_'.$response->status())),
                'status' => $response->status(),
            ];
        } catch (\Throwable $e) {
            Log::warning('Facebook profile inspect unreachable', ['error' => $e->getMessage()]);

            return ['ok' => false, 'reason' => 'unreachable'];
        }
    }

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

        try {
            $response = $this->request()->post($base.'/disable-post-approval', [
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

    private function request(): \Illuminate\Http\Client\PendingRequest
    {
        $token = (string) config('services.facebook.approver_token');
        $timeout = (int) config('services.facebook.approver_timeout', 110);
        $request = Http::timeout($timeout)->connectTimeout(5)->acceptJson();
        if ($token !== '') {
            $request = $request->withToken($token);
        }

        return $request;
    }
}
