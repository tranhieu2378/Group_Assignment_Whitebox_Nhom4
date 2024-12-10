<?php
use PHPUnit\Framework\TestCase;
// ./vendor/bin/phpunit tests/DetailTest.php
require_once __DIR__ . '/../frontend/function/function_chitiet.php';
class DetailTest extends TestCase
{
    private $mysqli;

    protected function setUp(): void
    {
        $this->mysqli = new mysqli("localhost", "root", "", "asm_php1");
    }

    // Test khi ID rỗng
    public function testGetProductDetailsEmptyId()
    {
        $result = getProductDetails($this->mysqli, '');
        $this->assertFalse($result);
    }


    // Test khi ID hợp lệ và có sản phẩm
    public function testGetProductDetailsWithValidId()
    {
        $result = getProductDetails($this->mysqli, 1);

        // Kiểm tra nếu có sản phẩm trong kết quả
        $row = $result->fetch_array();
        $this->assertNotEmpty($row);
        $this->assertEquals('CHERRY MỸ', $row['product_name']);
    }

    protected function tearDown(): void
    {
        $this->mysqli->close();
    }
}
?>
