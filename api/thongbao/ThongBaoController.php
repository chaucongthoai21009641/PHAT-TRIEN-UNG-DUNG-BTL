<?php
require_once 'config/database.php'; // Kết nối database
header('Content-Type: application/json');

class ThongBaoController
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Lấy danh sách thông báo
    public function index()
    {
        $sql = "SELECT * FROM thongbao";
        $result = $this->conn->query($sql);

        if (!$result) {
            http_response_code(500);
            echo json_encode(["error" => "Lỗi truy vấn dữ liệu"]);
            return;
        }

        $thongBaos = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($thongBaos);
    }

    // Tạo thông báo mới
    public function store($data)
    {
        if (!isset($data['tieuDe'], $data['url'], $data['tgBatDau'], $data['tgKetThuc'])) {
            http_response_code(400);
            echo json_encode(["error" => "Thiếu dữ liệu đầu vào"]);
            return;
        }

        $tieuDe = $data['tieuDe'];
        $url = $data['url'];
        $tgBatDau = $data['tgBatDau'];
        $tgKetThuc = $data['tgKetThuc'];

        if (strtotime($tgBatDau) >= strtotime($tgKetThuc)) {
            http_response_code(400);
            echo json_encode(["error" => "Thời gian kết thúc phải sau thời gian bắt đầu"]);
            return;
        }

        $sql = "INSERT INTO thongbao (tieuDe, url, tgBatDau, tgKetThuc) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $tieuDe, $url, $tgBatDau, $tgKetThuc);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Thêm thông báo thành công", "maTB" => $this->conn->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Lỗi khi thêm thông báo"]);
        }
    }

    // Lấy thông tin một thông báo
    public function show($id)
    {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID không hợp lệ"]);
            return;
        }

        $sql = "SELECT * FROM thongbao WHERE maTB = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $thongBao = $result->fetch_assoc();

        if ($thongBao) {
            echo json_encode($thongBao);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Không tìm thấy thông báo"]);
        }
    }

    // Cập nhật thông báo
    public function update($id, $data)
    {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID không hợp lệ"]);
            return;
        }

        // Lấy thông báo cũ
        $sql = "SELECT * FROM thongbao WHERE maTB = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $thongBao = $result->fetch_assoc();

        if (!$thongBao) {
            http_response_code(404);
            echo json_encode(["error" => "Không tìm thấy thông báo"]);
            return;
        }

        $tieuDe = $data['tieuDe'] ?? $thongBao['tieuDe'];
        $url = $data['url'] ?? $thongBao['url'];
        $tgBatDau = $data['tgBatDau'] ?? $thongBao['tgBatDau'];
        $tgKetThuc = $data['tgKetThuc'] ?? $thongBao['tgKetThuc'];

        if (strtotime($tgBatDau) >= strtotime($tgKetThuc)) {
            http_response_code(400);
            echo json_encode(["error" => "Thời gian kết thúc phải sau thời gian bắt đầu"]);
            return;
        }

        $sql = "UPDATE thongbao SET tieuDe = ?, url = ?, tgBatDau = ?, tgKetThuc = ? WHERE maTB = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $tieuDe, $url, $tgBatDau, $tgKetThuc, $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Cập nhật thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Lỗi khi cập nhật"]);
        }
    }

    // Xóa thông báo
    public function destroy($id)
    {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID không hợp lệ"]);
            return;
        }

        // Lấy thông báo cần xóa
        $sql = "SELECT * FROM thongbao WHERE maTB = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $thongBao = $result->fetch_assoc();

        if (!$thongBao) {
            http_response_code(404);
            echo json_encode(["error" => "Không tìm thấy thông báo"]);
            return;
        }

        // Xóa thông báo
        $sql = "DELETE FROM thongbao WHERE maTB = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Xóa thông báo thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Lỗi khi xóa"]);
        }
    }
}
