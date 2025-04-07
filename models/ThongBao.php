<?php
class ThongBao
{
    private $conn;
    private $table = "thongbao";

    private $maTB = "maTB";
    private $tieuDe = "tieuDe";
    private $url = "url";
    private $tgBatDau = "tgBatDau";
    private $tgKetThuc = "tgKetThuc";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả tài khoản
    public function selectAll()
    {
        $query = "SELECT * FROM $this->table ORDER BY $this->maTB DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result();
    }
}
