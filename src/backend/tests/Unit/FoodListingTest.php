<?php

namespace Tests\Unit;

use App\Models\FoodListing;
use PHPUnit\Framework\TestCase;

/**
 * Unit test untuk method helper di model FoodListing.
 * Menggunakan pola AAA (Arrange-Act-Assert).
 * Tidak membutuhkan koneksi database karena semua method yang diuji adalah pure function.
 */
class FoodListingTest extends TestCase
{
    // ── Helper: buat FoodListing instance tanpa Eloquent cast ─────────
    private function makeListing(int $originalPrice, int $discountPrice, int $stock = 5, string $status = 'available'): FoodListing
    {
        $listing = new FoodListing();
        // setRawAttributes bypass semua cast Eloquent (decimal, enum, dll)
        $listing->setRawAttributes([
            'original_price' => $originalPrice,
            'discount_price' => $discountPrice,
            'stock_qty'      => $stock,
            'status'         => $status,
        ], true);
        return $listing;
    }

    // ════════════════════════════════════════════════════════════════════
    // discountPercent()
    // ════════════════════════════════════════════════════════════════════

    /** @test */
    public function discountPercent_returns_correct_percentage_for_normal_discount(): void
    {
        // Arrange — harga normal 25.000, harga surplus 15.000 → diskon 40%
        $listing = $this->makeListing(25000, 15000);

        // Act
        $percent = $listing->discountPercent();

        // Assert
        $this->assertEquals(40, $percent);
    }

    /** @test */
    public function discountPercent_returns_50_when_discount_price_is_half_of_original(): void
    {
        // Arrange — harga normal 50.000, harga surplus 25.000 → diskon 50%
        $listing = $this->makeListing(50000, 25000);

        // Act
        $percent = $listing->discountPercent();

        // Assert
        $this->assertEquals(50, $percent);
    }

    /** @test */
    public function discountPercent_returns_0_when_original_price_is_zero(): void
    {
        // Arrange — original price 0 → hindari division by zero, return 0
        $listing = $this->makeListing(0, 0);

        // Act
        $percent = $listing->discountPercent();

        // Assert
        $this->assertEquals(0, $percent);
    }

    /** @test */
    public function discountPercent_returns_0_when_prices_are_equal(): void
    {
        // Arrange — harga sama → diskon 0%
        $listing = $this->makeListing(20000, 20000);

        // Act
        $percent = $listing->discountPercent();

        // Assert
        $this->assertEquals(0, $percent);
    }

    /** @test */
    public function discountPercent_returns_integer_type(): void
    {
        // Arrange
        $listing = $this->makeListing(30000, 10000);

        // Act
        $percent = $listing->discountPercent();

        // Assert — harus return int, bukan float
        $this->assertIsInt($percent);
    }

    /** @test */
    public function discountPercent_rounds_correctly_for_non_round_percentage(): void
    {
        // Arrange — 10000 / 30000 = 66.666...% diskon → dibulatkan jadi 67
        $listing = $this->makeListing(30000, 10000);

        // Act
        $percent = $listing->discountPercent();

        // Assert
        $this->assertEquals(67, $percent);
    }

    /** @test */
    public function discountPercent_returns_100_when_discount_price_is_zero(): void
    {
        // Arrange — harga surplus 0 → diskon 100%
        $listing = $this->makeListing(25000, 0);

        // Act
        $percent = $listing->discountPercent();

        // Assert
        $this->assertEquals(100, $percent);
    }

    // ════════════════════════════════════════════════════════════════════
    // isAvailable()
    // ════════════════════════════════════════════════════════════════════

/** @test */
public function discountPercent_is_always_less_than_or_equal_to_100(): void
{
    // Arrange — harga surplus tidak bisa lebih dari harga normal
    $listing = $this->makeListing(10000, 5000);

    // Act
    $percent = $listing->discountPercent();

    // Assert
    $this->assertLessThanOrEqual(100, $percent);
    $this->assertGreaterThanOrEqual(0, $percent);
}

    /** @test */
    public function isAvailable_returns_false_when_stock_is_zero(): void
    {
        // Arrange — stok habis meskipun status available
        $listing = $this->makeListing(25000, 15000, 0, 'available');

        // Act
        $result = $listing->isAvailable();

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function isAvailable_returns_false_when_status_is_unavailable(): void
    {
        // Arrange — stok ada tapi status nonaktif
        $listing = $this->makeListing(25000, 15000, 5, 'unavailable');

        // Act
        $result = $listing->isAvailable();

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function isAvailable_returns_false_when_status_is_sold_out(): void
    {
        // Arrange
        $listing = $this->makeListing(25000, 15000, 0, 'sold_out');

        // Act
        $result = $listing->isAvailable();

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function isAvailable_returns_false_when_stock_is_zero_and_status_is_not_available(): void
    {
        // Arrange — edge case: dua kondisi gagal sekaligus
        $listing = $this->makeListing(25000, 15000, 0, 'unavailable');

        // Act
        $result = $listing->isAvailable();

        // Assert
        $this->assertFalse($result);
    }
}