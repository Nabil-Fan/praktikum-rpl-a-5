<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade');

            $table->string('payment_gateway', 50);
            $table->string('transaction_id', 255)->nullable();
            $table->decimal('amount', 10, 2);

            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'refunded',
                'expired'
            ])->default('pending');

            $table->string('payment_proof_url', 500)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['order_id']);

            $table->dropColumn([
                'order_id',
                'payment_gateway',
                'transaction_id',
                'amount',
                'status',
                'payment_proof_url',
                'paid_at',
                'expired_at',
            ]);
        });
    }
};