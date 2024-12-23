<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../frontend/function/function_register.php';
// ./vendor/bin/phpunit tests/RegisterTest.php
class RegisterTest extends TestCase
{
    private $mysqli;

    protected function setUp(): void
    {
        $this->mysqli = new mysqli("localhost", "root", "", "asm_php1");
        $_SESSION = [];
    }

    // Testcase 1: Kiểm tra đăng ký rỗng
    public function test_DK_001()
    {
        $result = registerUser($this->mysqli, "", "", "", "", "");
        $this->assertEquals("Vui lòng nhập đầy đủ thông tin đăng ký", $result);
    }

    // Testcase 2: Kiểm tra đăng ký với Email trùng
    public function test_DK_002()
    {
        $email = "tester999@gmail.com";
        $this->mysqli->query("INSERT INTO quanli_user (user_name, user_code, user_email, user_phone, user_address, user_password) 
            VALUES ('Test User', 0, '$email', '123456789', 'Test Address', 'password')");

        $result = registerUser($this->mysqli, "Tester 999", $email, "0987654321", "12 Nguyễn Trãi", "123456");
        $this->assertEquals("Email đã được sử dụng. Vui lòng chọn email khác.", $result);

        // Xóa dữ liệu sau test
        $this->mysqli->query("DELETE FROM quanli_user WHERE user_email = '$email'");
    }

    // Testcase 3: Kiểm tra đăng ký thành công
    public function test_DK_003()
    {
        $result = registerUser($this->mysqli, "New User", "tester998@gmail.com", "0987654321", "New Address", "newpassword");
        $this->assertEquals("Đăng ký thành công. Mời bạn đăng nhập.", $result);

        // Xóa dữ liệu sau test
        $this->mysqli->query("DELETE FROM quanli_user WHERE user_email = 'tester998@gmail.com'");
    }

}
?>
