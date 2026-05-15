<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel merchant_profiles menyimpan detail dan status verifikasi mitra merchant.
     * Relasi 1:1 dengan tabel users (UNIQUE pada user_id).
     * Koordinat lat/lng divalidasi di level aplikasi DAN database (CHECK constraint).
     */
    public function up(): void
    {
        Schema::create('merchant_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('business_name', 150);
            $table->text('business_address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('business_license_url', 500)->nullable();
            $table->string('halal_cert_url', 500)->nullable();
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Foreign key ke users
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        // CHECK constraint untuk validasi range koordinat di level database
        DB::statement('ALTER TABLE merchant_profiles ADD CONSTRAINT chk_latitude CHECK (latitude BETWEEN -90 AND 90)');
        DB::statement('ALTER TABLE merchant_profiles ADD CONSTRAINT chk_longitude CHECK (longitude BETWEEN -180 AND 180)');
    }

    public function down(): void
    {
        Schema::dropIfExists('merchant_profiles');
    }
};