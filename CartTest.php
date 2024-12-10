<?php
// ./vendor/bin/phpunit --configuration phpunit.xml
// ./vendor/bin/phpunit tests/CartTest.php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../frontend/function/function_cart.php';

class CartTest extends TestCase
{
    protected function setUp(): void
    {
        // Khởi tạo session giả
        $_SESSION = [];
    }

    // Testcase 1: Thêm mới một sản phẩm vào giỏ hàng
    public function test_TGH_001() {
        addToCart("Dưa lưới Đài Loan", 6, 120000, "images/traicay6.jpg", 2);

        $this->assertArrayHasKey(6, $_SESSION['cart']);
        $this->assertEquals(2, $_SESSION['cart'][6]['soluong']);
        $this->assertEquals("Dưa lưới Đài Loan", $_SESSION['cart'][6]['tensanpham']);
    }

    // Testcase 2: Thêm số lượng sản phẩm đã tồn tại trong giỏ hàng
    public function test_TGH_002() {
        $_SESSION['cart'][6] = [
            'tensanpham' => "Dưa lưới Đài Loan",
            'gia' => 120000,
            'hinhanh' => "images/traicay6.jpg",
            'soluong' => 2
        ];
        addToCart("Dưa lưới Đài Loan", 6, 120000, "images/traicay6.jpg", 3);
        
        $this->assertEquals(5, $_SESSION['cart'][6]['soluong']);
    }

    // Testcase 3: Cập nhật số lượng sản phẩm trong giỏ hàng
    public function test_UQ_001() {
        $_SESSION['cart'] = [
            1 => ['tensanpham' => 'Cherry Mỹ', 'soluong' => 2],
        ];
        updateQuantityInCart([1], [3]);
        // Expected result
        $this->assertEquals(3, $_SESSION['cart'][1]['soluong'], "Số lượng sản phẩm không được cập nhật chính xác.");
    }

    // Testcase 4: Xóa sản phẩm khi số lượng bằng 0
    public function test_UQ_002() {
        $_SESSION['cart'] = [
            2 => ['tensanpham' => 'Nho Hàn Quốc', 'soluong' => 5]
        ];
        updateQuantityInCart([2], [0]);
        // Kiểm tra sản phẩm 2 đã bị xóa
        $this->assertArrayNotHasKey(2, $_SESSION['cart'], "Sản phẩm không bị xóa khi số lượng bằng 0.");
    }

    // Testcase 5: Kiểm tra giỏ hàng trống hoặc dữ liệu trống
    public function test_UQ_003() {
        $_SESSION['cart'] = [];
        $productIds = [];
        $quantities = [];

        $result = updateQuantityInCart($productIds, $quantities);

        $this->assertFalse($result);
    }

    // Testcase 6: Xóa sản phẩm thành công
    public function test_RC_001() {
        $_SESSION['cart'][1] = ['tensanpham' => 'Cherry Mỹ', 'soluong' => 2];
        $result = removeFromCart(1);

        $this->assertArrayNotHasKey(1, $_SESSION['cart'], "Sản phẩm nên được xóa khỏi giỏ hàng.");
        $this->assertEquals("Xóa thành công", $result);
    }

    // Testcase 7: Xóa sản phẩm không tồn tại
    public function test_RC_002() {
        $result = removeFromCart(3);

        $this->assertEquals("Sản phẩm không tồn tại trong giỏ hàng", $result);
    }

    // Testcase 8: Tính tổng số tiền sản phẩm
    public function test_TM_001() {
        $this->assertEquals(20000, calculateTotalMoney(2, 10000));
        $this->assertEquals(0, calculateTotalMoney(0, 10000));
        $this->assertEquals(50000, calculateTotalMoney(5, 10000));
    }
}
