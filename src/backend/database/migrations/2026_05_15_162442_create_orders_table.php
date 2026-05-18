<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel orders menyimpan transaksi pemesanan user ke merchant.
     * Status pesanan: pending → confirmed → ready → completed
     *                         └──────────────────────→ rejected
     *                pending → expired (oleh scheduler jika melewati expires_at)
     *
     * Kolom payment_status TIDAK ada di sini — ada di tabel payments.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('merchant_id');
            $table->string('pickup_code', 10)->unique();
            $table->enum('status', ['pending', 'confirmed', 'ready', 'completed', 'rejected', 'expired'])
                  ->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['transfer', 'ewallet', 'cash', 'qris']);
            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign keys
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');

            $table->foreign('merchant_id')
                  ->references('id')
                  ->on('merchant_profiles')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};