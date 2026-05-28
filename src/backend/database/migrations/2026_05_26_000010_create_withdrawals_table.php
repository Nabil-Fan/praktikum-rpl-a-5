<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel withdrawals menyimpan riwayat permintaan pencairan dana merchant.
     *
     * Alur status:
     *   pending → processing → completed
     *          └────────────→ rejected
     *
     * Cara hitung saldo yang bisa ditarik per merchant:
     *   SUM(payments.amount WHERE status = 'paid')
     *   MINUS
     *   SUM(withdrawals.amount WHERE status IN ('pending','processing','completed'))
     */
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id');
            $table->decimal('amount', 10, 2);
            $table->string('bank_name', 100);
            $table->string('bank_account_number', 50);
            $table->string('bank_account_name', 150);
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])
                  ->default('pending');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->string('transfer_proof_url', 500)->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Foreign keys
            $table->foreign('merchant_id')
                  ->references('id')
                  ->on('merchant_profiles')
                  ->onDelete('restrict');

            $table->foreign('admin_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};