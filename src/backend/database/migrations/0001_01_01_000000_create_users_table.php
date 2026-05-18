<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel users menyimpan semua akun pengguna platform.
     * Peran dibedakan menggunakan kolom `role` (user, merchant, admin).
     * Soft delete menggunakan kolom `deleted_at`.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 255)->unique();
            $table->string('password_hash', 255);
            $table->string('phone', 20)->nullable();
            $table->enum('role', ['user', 'merchant', 'admin'])->default('user');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};