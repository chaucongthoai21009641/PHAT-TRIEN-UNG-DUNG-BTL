<?php

class CaLam
{
    private $conn;
    private $table = "calam";

    private $maCL = "maCL";
    private $tenCa = "tenCa";
    private $gioCheckInSom = "gioCheckInSom";
    private $gioCheckOutMuon = "gioCheckOutMuon";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả ca làm
    public function selectAll()
    {
        $query = "SELECT * FROM $this->table ORDER BY $this->maCL DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Lấy một ca làm theo ID
    public function getById($maCL)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE maCL = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $maCL);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Thêm mới ca làm
    public function create($tenCa, $gioCheckInSom, $gioCheckOutMuon)
    {
        $query = "INSERT INTO " . $this->table . " (tenCa, gioCheckInSom, gioCheckOutMuon) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $tenCa, $gioCheckInSom, $gioCheckOutMuon);
        return $stmt->execute();
    }

    // Cập nhật ca làm
    public function update($maCL, $tenCa, $gioCheckInSom, $gioCheckOutMuon)
    {
        $query = "UPDATE " . $this->table . " SET tenCa = ?, gioCheckInSom = ?, gioCheckOutMuon = ? WHERE maCL = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $tenCa, $gioCheckInSom, $gioCheckOutMuon, $maCL);
        return $stmt->execute();
    }

    // Xóa ca làm
    public function delete($maCL)
    {
        $query = "DELETE FROM " . $this->table . " WHERE maCL = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $maCL);
        return $stmt->execute();
    }
}
