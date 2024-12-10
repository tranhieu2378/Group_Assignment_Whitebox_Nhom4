<?php 
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../frontend/handleuser/doimatkhau.php';
// ./vendor/bin/phpunit tests/ChangePwdTest.php
class ChangePwdTest extends TestCase
{
    private $mysqli;
    private $tendangnhap;
    private $matkhaucu;
    
    // Setup cho PHPUnit, tạo kết nối đến cơ sở dữ liệu thật
    protected function setUp(): void
    {
          $this->mysqli = new mysqli("localhost", "root", "", "asm_php1");
          $this->tendangnhap = 'testerhieu@gmail.com';
          $this->matkhaucu = '1234567';
          $this->resetPassword();
     }

     // Kiểm tra trường hợp mật khẩu cũ sai
     public function test_UP_001()
     {
          $result = doiMatKhau($this->mysqli, $this->tendangnhap, 'wrongpassword', 'newpassword1', 'newpassword1'); 
          $this->assertStringContainsString("Mật khẩu cũ sai", $result['errors']);
     }

     // Kiểm tra trường hợp mật khẩu mới giống mật khẩu cũ
     public function test_UP_002()
     {
          $result = doiMatKhau($this->mysqli, $this->tendangnhap, $this->matkhaucu, '1234567', '1234567');   
          $this->assertStringContainsString("Mật khẩu mới không được giống mật khẩu cũ", $result['errors']);
     }

     // Kiểm tra trường hợp mật khẩu mới quá ngắn
     public function test_UP_003()
     {
          $result = doiMatKhau($this->mysqli, $this->tendangnhap, $this->matkhaucu, '12345', '12345');       
          $this->assertStringContainsString("Mật khẩu mới phải trên 6 ký tự", $result['errors']);
     }

     // Kiểm tra trường hợp mật khẩu mới và xác nhận mật khẩu không khớp
     public function test_UP_004()
     {
          $result = doiMatKhau($this->mysqli, $this->tendangnhap, $this->matkhaucu, 'newpassword1', 'newpassword2');
          $this->assertStringContainsString("Nhập lại mật khẩu không giống nhau", $result['errors']);
     }

     // Kiểm tra trường hợp mật khẩu thay đổi thành công
     public function test_UP_005()
     {    
          $result = doiMatKhau($this->mysqli, $this->tendangnhap, $this->matkhaucu, 'newpassword123', 'newpassword123');    
          $this->assertStringContainsString("Thay đổi mật khẩu thành công", $result['thongBao']);    

          $sql = "SELECT * FROM quanli_user WHERE user_email = ?";
          $stmt = $this->mysqli->prepare($sql);
          $stmt->bind_param("s", $this->tendangnhap);
          $stmt->execute();
          $result = $stmt->get_result();
          $user = $result->fetch_assoc();
          
          $this->assertEquals('newpassword123', $user['user_password']);
          
          // Đổi lại mk như cũ
          $this->resetPassword();
     }

     // Phương thức để khôi phục lại mật khẩu cũ
     private function resetPassword()
     {
          $sql = "UPDATE quanli_user SET user_password = ? WHERE user_email = ?";
          $stmt = $this->mysqli->prepare($sql);
          $stmt->bind_param("ss", $this->matkhaucu, $this->tendangnhap);
          $stmt->execute();
     }

     // Đảm bảo đóng kết nối sau khi kiểm thử
     protected function tearDown(): void
     {
          $this->mysqli->close();
     }
}
 
?>