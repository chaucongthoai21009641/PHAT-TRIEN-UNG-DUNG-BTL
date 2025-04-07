<?php

class ChiTietCaLam
{
    private $conn;
    private $table = "chitietcalam";

    private $maCTCL = "maCTCL";
    private $thuTrongTuan = "thuTrongTuan";
    private $tgBatDau = "tgBatDau";
    private $tgKetThuc = "tgKetThuc";
    private $tgBatDauNghi = "tgBatDauNghi";
    private $tgKetThucNghi = "tgKetThucNghi";
    private $heSoLuong = "heSoLuong";
    private $tienThuong = "tienThuong";

    private $maCL = "maCL";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Lấy tất cả chi tiết ca làm
     */
    public function selectAll()
    {
        $query = "SELECT * FROM $this->table ORDER BY $this->maCTCL DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Lấy tất cả chi tiết ca làm theo mã ca làm (maCL)
     */
    function getAllChiTietCaLamByCaLam($conn, $maCL)
    {
        $maCL = intval($maCL);
        $sql = "SELECT * FROM chitietcalam WHERE maCL = $maCL";
        $result = $conn->query($sql);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
    }

    /**
     * Lấy chi tiết một ca làm theo maCTCL
     */
    function getChiTietCaLam($conn, $maCTCL)
    {
        $maCTCL = intval($maCTCL);
        $sql = "SELECT * FROM chitietcalam WHERE maCTCL = $maCTCL";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(["message" => "Không tìm thấy chi tiết ca làm"]);
        }
    }

    /**
     * Thêm chi tiết ca làm mới
     */
    function addChiTietCaLam($conn)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['thuTrongTuan'], $data['tgBatDau'], $data['tgKetThuc'], $data['maCL'])) {
            echo json_encode(["message" => "Thiếu dữ liệu"]);
            return;
        }

        $thuTrongTuan = $conn->real_escape_string($data['thuTrongTuan']);
        $tgBatDau = $conn->real_escape_string($data['tgBatDau']);
        $tgKetThuc = $conn->real_escape_string($data['tgKetThuc']);
        $tgBatDauNghi = isset($data['tgBatDauNghi']) ? $conn->real_escape_string($data['tgBatDauNghi']) : 'NULL';
        $tgKetThucNghi = isset($data['tgKetThucNghi']) ? $conn->real_escape_string($data['tgKetThucNghi']) : 'NULL';
        $heSoLuong = isset($data['heSoLuong']) ? floatval($data['heSoLuong']) : 1.0;
        $tienThuong = isset($data['tienThuong']) ? floatval($data['tienThuong']) : 0;
        $maCL = intval($data['maCL']);

        $sql = "INSERT INTO chitietcalam (thuTrongTuan, tgBatDau, tgKetThuc, tgBatDauNghi, tgKetThucNghi, heSoLuong, tienThuong, maCL) 
            VALUES ('$thuTrongTuan', '$tgBatDau', '$tgKetThuc', '$tgBatDauNghi', '$tgKetThucNghi', $heSoLuong, $tienThuong, $maCL)";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Thêm chi tiết ca làm thành công"]);
        } else {
            echo json_encode(["message" => "Lỗi: " . $conn->error]);
        }
    }

    /**
     * Cập nhật chi tiết ca làm
     */
    function updateChiTietCaLam($conn, $data)
    {
        if (!isset($data['maCTCL'])) {
            echo json_encode(["message" => "Thiếu dữ liệu"]);
            return;
        }

        $maCTCL = intval($data['maCTCL']);
        $thuTrongTuan = isset($data['thuTrongTuan']) ? $conn->real_escape_string($data['thuTrongTuan']) : null;
        $tgBatDau = isset($data['tgBatDau']) ? $conn->real_escape_string($data['tgBatDau']) : null;
        $tgKetThuc = isset($data['tgKetThuc']) ? $conn->real_escape_string($data['tgKetThuc']) : null;
        $tgBatDauNghi = isset($data['tgBatDauNghi']) ? $conn->real_escape_string($data['tgBatDauNghi']) : null;
        $tgKetThucNghi = isset($data['tgKetThucNghi']) ? $conn->real_escape_string($data['tgKetThucNghi']) : null;
        $heSoLuong = isset($data['heSoLuong']) ? floatval($data['heSoLuong']) : null;
        $tienThuong = isset($data['tienThuong']) ? floatval($data['tienThuong']) : null;

        $sql = "UPDATE chitietcalam SET 
                thuTrongTuan='$thuTrongTuan', 
                tgBatDau='$tgBatDau', 
                tgKetThuc='$tgKetThuc', 
                tgBatDauNghi='$tgBatDauNghi', 
                tgKetThucNghi='$tgKetThucNghi', 
                heSoLuong=$heSoLuong, 
                tienThuong=$tienThuong 
            WHERE maCTCL=$maCTCL";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Cập nhật thành công"]);
        } else {
            echo json_encode(["message" => "Lỗi: " . $conn->error]);
        }
    }

    /**
     * Xóa chi tiết ca làm
     */
    function deleteChiTietCaLam($conn, $maCTCL)
    {
        $maCTCL = intval($maCTCL);
        $sql = "DELETE FROM chitietcalam WHERE maCTCL=$maCTCL";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "Xóa thành công"]);
        } else {
            echo json_encode(["message" => "Lỗi: " . $conn->error]);
        }
    }
}
