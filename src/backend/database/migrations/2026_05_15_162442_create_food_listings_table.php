<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel food_listings menyimpan daftar makanan surplus yang dipublikasikan merchant.
     * Soft delete menggunakan kolom `deleted_at`.
     * Query aktif WAJIB filter WHERE deleted_at IS NULL.
     */
    public function up(): void
    {
        Schema::create('food_listings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id');
            $table->unsignedBigInteger('category_id');
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->decimal('original_price', 10, 2);
            $table->decimal('discount_price', 10, 2);
            $table->integer('stock_qty')->default(0);
            $table->string('photo_url', 500)->nullable();
        $table->dateTime('pickup_start')->nullable();
        $table->dateTime('pickup_end')->nullable();
            $table->enum('status', ['available', 'unavailable', 'sold_out'])->default('available');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('deleted_at')->nullable();

            // Foreign keys
            $table->foreign('merchant_id')
                  ->references('id')
                  ->on('merchant_profiles')
                  ->onDelete('cascade');

            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_listings');
    }
};