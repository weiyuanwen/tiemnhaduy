<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')->constrained('service_orders')->cascadeOnDelete();
            $table->string('bank_txn_id')->unique();
            $table->unsignedBigInteger('amount');
            $table->string('description')->nullable();
            $table->timestamp('matched_at');
            $table->timestamps();

            $table->index('service_order_id');
        });

        DB::table('services')
            ->where('id', 1)
            ->where('price', 100000)
            ->update(['price' => 150000]);
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
