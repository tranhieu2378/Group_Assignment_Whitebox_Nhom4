<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../frontend/function/function_logout.php';

class LogoutTest extends TestCase
{
    // Khởi tạo session giả để thử nghiệm
    protected function setUp(): void
    {
        $_SESSION = [
            'login_home' => 'test@example.com',
            'login_name' => 'Test User',
            'login_id' => 1,
            'user_code' => '123456',
            'admin_home' => true
        ];
    }

    // Test case cho hàm logoutUser
    public function testLogoutUser()
    {
        // Gọi hàm logoutUser
        $result = logoutUser();
        // Kiểm tra các biến session đã được xóa
        $this->assertArrayNotHasKey('login_home', $_SESSION);
        $this->assertArrayNotHasKey('login_name', $_SESSION);
        $this->assertArrayNotHasKey('login_id', $_SESSION);
        $this->assertArrayNotHasKey('user_code', $_SESSION);
        $this->assertArrayNotHasKey('admin_home', $_SESSION);
        // Kiểm tra thông báo kết quả trả về
        $this->assertEquals("Đăng xuất thành công.", $result);
    }
}
?>
