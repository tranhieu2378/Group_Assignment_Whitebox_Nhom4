<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../frontend/function/function_search.php';
// ./vendor/bin/phpunit tests/SearchTest.php
class SearchTest extends TestCase
{
    private $mysqli;

    protected function setUp(): void
    {
        $this->mysqli = new mysqli("localhost", "root", "", "asm_php1");
    }

    protected function tearDown(): void
    {
        $this->mysqli->close();
    }

    public function testSearchProductsWithKeyword()
    {
        // Giả sử trong CSDL đã có sản phẩm tên 'Dưa lưới'
        $result = searchProducts($this->mysqli, 'Dưa lưới');
        // Kiểm tra số lượng sản phẩm trả về từ CSDL
        $this->assertGreaterThan(0, $result['count']);
        $this->assertStringContainsString('Dưa lưới', $result['title']);
    }

    public function testSearchProductsNoResults()
    {
        $result = searchProducts($this->mysqli, 'Không Tồn Tại');
        // Kiểm tra không có sản phẩm nào trả về
        $this->assertEquals(0, $result['count']);
    }
}
?>