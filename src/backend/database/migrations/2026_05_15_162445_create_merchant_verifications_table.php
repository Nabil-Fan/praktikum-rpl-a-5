<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel merchant_verifications menyimpan log riwayat keputusan verifikasi admin.
     * Mendukung audit trail — setiap keputusan approve/reject tercatat lengkap.
     * Satu merchant bisa punya beberapa record (jika diajukan ulang setelah ditolak).
     */
    public function up(): void
    {
        Schema::create('merchant_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id');
            $table->unsignedBigInteger('admin_id');
            $table->enum('action', ['approved', 'rejected']);
            $table->text('notes')->nullable();
            $table->timestamp('actioned_at')->useCurrent();

            // Foreign keys
            $table->foreign('merchant_id')
                  ->references('id')
                  ->on('merchant_profiles')
                  ->onDelete('cascade');

            $table->foreign('admin_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('merchant_verifications');
    }
};