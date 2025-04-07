<?php
class TaiLieu
{
    private $conn;
    private $table = "tailieu";

    private $maTL = "maTL";
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
        $query = "SELECT * FROM $this->table ORDER BY $this->maTL DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result();
    }
}
