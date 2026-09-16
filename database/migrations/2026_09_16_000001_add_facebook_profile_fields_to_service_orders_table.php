<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->string('facebook_name')->nullable()->after('facebook_profile_link');
            $table->string('facebook_id', 32)->nullable()->after('facebook_name');
            $table->timestamp('facebook_approval_disabled_at')->nullable()->after('extension_result');
            $table->json('facebook_approval_result')->nullable()->after('facebook_approval_disabled_at');
        });
    }

    public function down(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropColumn([
                'facebook_name',
                'facebook_id',
                'facebook_approval_disabled_at',
                'facebook_approval_result',
            ]);
        });
    }
};
