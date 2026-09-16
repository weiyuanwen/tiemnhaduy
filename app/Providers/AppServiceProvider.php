<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('payment-create', function (Request $request) {
            return Limit::perMinutes(10, 5)->by('pay-create:'.$request->ip())->response(function () {
                return response()->json([
                    'status' => false,
                    'message' => 'Bạn thao tác quá nhanh. Vui lòng thử lại sau vài phút.',
                    'data' => null,
                ], 429);
            });
        });

        RateLimiter::for('payment-status', function (Request $request) {
            return Limit::perMinute(60)->by('pay-status:'.$request->ip());
        });
    }
}
