<?php 
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../frontend/function/function_login.php';

class LoginTest extends TestCase {
    protected $mysqli;

    protected function setUp(): void {
        $this->mysqli = new mysqli("localhost", "root", "", "asm_php1");
        $_SESSION = [];
    }

    protected function tearDown(): void {
        $this->mysqli->close();
    }

    // Testcase 1: Kiểm tra login rỗng
    public function testLoginEmptyFields() {
        $result = login($this->mysqli, '', '');
        $this->assertEquals("Vui lòng nhập đầy đủ tài khoản và password", $result);
    }

    // Kiểm tra login admin
    public function testLoginAdminSuccess() {
        $result = login($this->mysqli, 'admin@gmail.com', 'admin123456');
        $this->assertEquals("Xin chào Admin bạn đã đăng nhập thành công.", $result);
        $this->assertEquals(1, $_SESSION['admin_home']);
    }

    // Kiểm tra login user
    public function testLoginUserSuccess() {
        $result = login($this->mysqli, 'tranhieu123@gmail.com', '123456');
        $this->assertStringContainsString("Xin chào ", $result);
        $this->assertArrayHasKey('login_home', $_SESSION);
    }

    // Kiểm tra login sai mật khẩu
    public function testLoginInvalidCredentials() {
        $result = login($this->mysqli, 'tranhieu123@gmail.com', '1234567');
        $this->assertEquals("Tài khoản hoặc mật khẩu sai", $result);
    }
}

?>