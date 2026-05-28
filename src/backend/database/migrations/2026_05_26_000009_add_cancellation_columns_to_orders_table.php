<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom audit trail pembatalan pesanan ke tabel orders.
     * Ditempatkan setelah expired_at agar urutan kolom logis:
     * ordered_at → expires_at → confirmed_at → completed_at
     *           → rejected_at → expired_at → cancelled_at (baru)
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('cancelled_at')
                  ->nullable()
                  ->after('expired_at')
                  ->comment('Waktu pembatalan dilakukan. NULL jika pesanan tidak dibatalkan');

            $table->enum('cancelled_by', ['user', 'merchant', 'system'])
                  ->nullable()
                  ->after('cancelled_at')
                  ->comment('Aktor yang melakukan pembatalan. NULL jika pesanan tidak dibatalkan');

            $table->text('cancellation_reason')
                  ->nullable()
                  ->after('cancelled_by')
                  ->comment('Alasan pembatalan untuk keperluan audit. NULL jika tidak dibatalkan');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['cancelled_at', 'cancelled_by', 'cancellation_reason']);
        });
    }
};