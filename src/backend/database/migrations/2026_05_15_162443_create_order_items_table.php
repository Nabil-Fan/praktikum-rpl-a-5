<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Junction table antara orders dan food_listings.
     * Satu pesanan dapat berisi banyak item makanan.
     *
     * Kolom `listing_name` dan `unit_price` adalah SNAPSHOT saat transaksi.
     * Ini penting agar data historis tidak rusak jika listing diubah atau dihapus.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade');

            $table->foreignId('food_listing_id')
                ->constrained('food_listings')
                ->onDelete('restrict');

            $table->string('listing_name', 150);
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};