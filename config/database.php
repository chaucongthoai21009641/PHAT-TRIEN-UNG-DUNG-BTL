<?php
class Database
{
    private $host = "localhost";   // Địa chỉ database
    private $username = "root";    // Username database
    private $password = "";        // Mật khẩu database
    private $dbname = "dbhrm2"; // Tên database của bạn
    public $conn;

    // Hàm kết nối Database sử dụng MySQLi
    // public function connect() {
    //     // Thêm $this->dbname vào kết nối
    //     $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

    //     // Kiểm tra lỗi kết nối
    //     if ($this->conn->connect_error) {
    //         die("Lỗi kết nối: " . $this->conn->connect_error);
    //     }

    //     // Bật chế độ báo lỗi MySQLi
    //     mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    //     return $this->conn;
    // }
    public function connect()
    {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            die("Connection error: " . $e->getMessage());
        }
        return $this->conn;
    }
}

// Kiểm tra kết nối
$db = new Database();
$db->connect();
