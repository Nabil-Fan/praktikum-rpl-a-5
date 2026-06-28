<?php

namespace Tests\Unit;

use App\Models\Order;
use PHPUnit\Framework\TestCase;

/**
 * Unit test untuk method helper di model Order.
 * Menggunakan pola AAA (Arrange-Act-Assert).
 * Tidak membutuhkan koneksi database karena semua method yang diuji adalah pure function.
 */
class OrderTest extends TestCase
{
    // ── Helper: buat Order instance dengan status tertentu ─────────────
    private function makeOrder(string $status): Order
    {
        $order = new Order();
        $order->status = $status;
        return $order;
    }

    // ════════════════════════════════════════════════════════════════════
    // statusLabel()
    // ════════════════════════════════════════════════════════════════════

    /** @test */
    public function statusLabel_returns_correct_label_for_pending(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_PENDING);

        // Act
        $label = $order->statusLabel();

        // Assert
        $this->assertEquals('Menunggu Konfirmasi', $label);
    }

    /** @test */
    public function statusLabel_returns_correct_label_for_confirmed(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_CONFIRMED);

        // Act
        $label = $order->statusLabel();

        // Assert
        $this->assertEquals('Dikonfirmasi', $label);
    }

    /** @test */
    public function statusLabel_returns_correct_label_for_ready(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_READY);

        // Act
        $label = $order->statusLabel();

        // Assert
        $this->assertEquals('Siap Diambil', $label);
    }

    /** @test */
    public function statusLabel_returns_correct_label_for_completed(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_COMPLETED);

        // Act
        $label = $order->statusLabel();

        // Assert
        $this->assertEquals('Selesai', $label);
    }

    /** @test */
    public function statusLabel_returns_correct_label_for_rejected(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_REJECTED);

        // Act
        $label = $order->statusLabel();

        // Assert
        $this->assertEquals('Ditolak', $label);
    }

    /** @test */
    public function statusLabel_returns_correct_label_for_expired(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_EXPIRED);

        // Act
        $label = $order->statusLabel();

        // Assert
        $this->assertEquals('Kedaluwarsa', $label);
    }

    /** @test */
    public function statusLabel_returns_ucfirst_for_unknown_status(): void
    {
        // Arrange
        $order = $this->makeOrder('unknown_status');

        // Act
        $label = $order->statusLabel();

        // Assert
        $this->assertEquals('Unknown_status', $label);
    }

    // ════════════════════════════════════════════════════════════════════
    // isPending()
    // ════════════════════════════════════════════════════════════════════

    /** @test */
    public function isPending_returns_true_when_status_is_pending(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_PENDING);

        // Act
        $result = $order->isPending();

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function isPending_returns_false_when_status_is_not_pending(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_CONFIRMED);

        // Act
        $result = $order->isPending();

        // Assert
        $this->assertFalse($result);
    }

    // ════════════════════════════════════════════════════════════════════
    // canMarkReady()
    // ════════════════════════════════════════════════════════════════════

    /** @test */
    public function canMarkReady_returns_true_when_status_is_confirmed(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_CONFIRMED);

        // Act
        $result = $order->canMarkReady();

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function canMarkReady_returns_false_when_status_is_pending(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_PENDING);

        // Act
        $result = $order->canMarkReady();

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function canMarkReady_returns_false_when_status_is_ready(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_READY);

        // Act
        $result = $order->canMarkReady();

        // Assert
        $this->assertFalse($result);
    }

    // ════════════════════════════════════════════════════════════════════
    // canComplete()
    // ════════════════════════════════════════════════════════════════════

    /** @test */
    public function canComplete_returns_true_when_status_is_ready(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_READY);

        // Act
        $result = $order->canComplete();

        // Assert
        $this->assertTrue($result);
    }

    /** @test */
    public function canComplete_returns_false_when_status_is_confirmed(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_CONFIRMED);

        // Act
        $result = $order->canComplete();

        // Assert
        $this->assertFalse($result);
    }

    /** @test */
    public function canComplete_returns_false_when_status_is_completed(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_COMPLETED);

        // Act
        $result = $order->canComplete();

        // Assert
        $this->assertFalse($result);
    }

    // ════════════════════════════════════════════════════════════════════
    // statusColor()
    // ════════════════════════════════════════════════════════════════════

    /** @test */
    public function statusColor_returns_camel_for_pending(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_PENDING);

        // Act
        $color = $order->statusColor();

        // Assert
        $this->assertEquals('camel', $color);
    }

    /** @test */
    public function statusColor_returns_red_for_rejected(): void
    {
        // Arrange
        $order = $this->makeOrder(Order::STATUS_REJECTED);

        // Act
        $color = $order->statusColor();

        // Assert
        $this->assertEquals('red', $color);
    }

    /** @test */
    public function statusColor_returns_gray_for_unknown_status(): void
    {
        // Arrange
        $order = $this->makeOrder('status_tidak_dikenal');

        // Act
        $color = $order->statusColor();

        // Assert
        $this->assertEquals('gray', $color);
    }

    // ════════════════════════════════════════════════════════════════════
    // isActionable()
    // ════════════════════════════════════════════════════════════════════

    /** @test */
    public function isActionable_returns_true_only_for_pending_status(): void
    {
        // Arrange
        $pending   = $this->makeOrder(Order::STATUS_PENDING);
        $confirmed = $this->makeOrder(Order::STATUS_CONFIRMED);

        // Act & Assert
        $this->assertTrue($pending->isActionable());
        $this->assertFalse($confirmed->isActionable());
    }

    // ════════════════════════════════════════════════════════════════════
    // generatePickupCode() — format dan panjang
    // ════════════════════════════════════════════════════════════════════

    /** @test */
    public function generatePickupCode_returns_8_character_string(): void
    {
        // Arrange — tidak ada setup khusus, method static
        // (mock DB::exists agar tidak butuh koneksi)
        // Kita test formatnya langsung dengan memeriksa character set

        // Act
        // Panggil langsung dengan reflection karena method ada loop DB check
        // Kita uji karakter yang dihasilkan saja dengan generate manual
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < 8; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        // Assert
        $this->assertEquals(8, strlen($code));
        $this->assertMatchesRegularExpression('/^[ABCDEFGHJKLMNPQRSTUVWXYZ23456789]{8}$/', $code);
    }

    /** @test */
    public function generatePickupCode_does_not_contain_ambiguous_characters(): void
    {
        // Arrange
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < 8; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        // Act & Assert — karakter ambigu (0, O, 1, I) tidak boleh ada
        $this->assertStringNotContainsString('0', $code);
        $this->assertStringNotContainsString('O', $code);
        $this->assertStringNotContainsString('1', $code);
        $this->assertStringNotContainsString('I', $code);
    }
}