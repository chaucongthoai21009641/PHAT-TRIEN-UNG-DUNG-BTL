<?php
require_once 'config/database.php'; // Kết nối database
header('Content-Type: application/json');

class HopThoaiController
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Lấy danh sách hộp thoại
    public function index()
    {
        $sql = "SELECT * FROM hopthoai";
        $result = $this->conn->query($sql);

        if (!$result) {
            http_response_code(500);
            echo json_encode(["error" => "Lỗi truy vấn dữ liệu"]);
            return;
        }

        $hopThoais = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($hopThoais);
    }

    // Tạo hộp thoại mới
    public function store($data, $file)
    {
        if (!isset($data['tieuDe'], $data['noiDung'])) {
            http_response_code(400);
            echo json_encode(["error" => "Thiếu dữ liệu đầu vào"]);
            return;
        }

        $tieuDe = $data['tieuDe'];
        $noiDung = $data['noiDung'];
        $url = $data['url'] ?? null;
        $soLanHienThi = $data['soLanHienThi'] ?? 0;
        $tgBatDau = $data['tgBatDau'] ?? null;
        $tgKetThuc = $data['tgKetThuc'] ?? null;
        $iconHienThi = null;

        if ($tgBatDau && $tgKetThuc && strtotime($tgBatDau) >= strtotime($tgKetThuc)) {
            http_response_code(400);
            echo json_encode(["error" => "Thời gian kết thúc phải sau thời gian bắt đầu"]);
            return;
        }

        // Xử lý upload file
        if ($file && isset($file['iconHienThi']) && $file['iconHienThi']['error'] == 0) {
            $targetDir = "uploads/";
            $fileName = time() . "_" . basename($file['iconHienThi']['name']);
            $targetFilePath = $targetDir . $fileName;
            move_uploaded_file($file['iconHienThi']['tmp_name'], $targetFilePath);
            $iconHienThi = $targetFilePath;
        }

        $sql = "INSERT INTO hopthoai (tieuDe, noiDung, url, soLanHienThi, tgBatDau, tgKetThuc, iconHienThi) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssss", $tieuDe, $noiDung, $url, $soLanHienThi, $tgBatDau, $tgKetThuc, $iconHienThi);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Thêm hộp thoại thành công", "maHT" => $this->conn->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Lỗi khi thêm hộp thoại"]);
        }
    }

    // Lấy thông tin một hộp thoại
    public function show($id)
    {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID không hợp lệ"]);
            return;
        }

        $sql = "SELECT * FROM hopthoai WHERE maHT = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $hopThoai = $result->fetch_assoc();

        if ($hopThoai) {
            echo json_encode($hopThoai);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Không tìm thấy hộp thoại"]);
        }
    }

    // Cập nhật hộp thoại
    public function update($id, $data, $file)
    {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID không hợp lệ"]);
            return;
        }

        // Lấy hộp thoại cũ
        $sql = "SELECT * FROM hopthoai WHERE maHT = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $hopThoai = $result->fetch_assoc();

        if (!$hopThoai) {
            http_response_code(404);
            echo json_encode(["error" => "Không tìm thấy hộp thoại"]);
            return;
        }

        // Xử lý file icon mới (nếu có)
        $iconHienThi = $hopThoai['iconHienThi'];
        if ($file && isset($file['iconHienThi']) && $file['iconHienThi']['error'] == 0) {
            if ($iconHienThi && file_exists($iconHienThi)) {
                unlink($iconHienThi);
            }
            $targetDir = "uploads/";
            $fileName = time() . "_" . basename($file['iconHienThi']['name']);
            $targetFilePath = $targetDir . $fileName;
            move_uploaded_file($file['iconHienThi']['tmp_name'], $targetFilePath);
            $iconHienThi = $targetFilePath;
        }

        $sql = "UPDATE hopthoai SET 
                tieuDe = COALESCE(?, tieuDe), 
                noiDung = COALESCE(?, noiDung), 
                url = COALESCE(?, url), 
                soLanHienThi = COALESCE(?, soLanHienThi), 
                tgBatDau = COALESCE(?, tgBatDau), 
                tgKetThuc = COALESCE(?, tgKetThuc), 
                iconHienThi = ? 
                WHERE maHT = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssssssi",
            $data['tieuDe'] ?? null,
            $data['noiDung'] ?? null,
            $data['url'] ?? null,
            $data['soLanHienThi'] ?? null,
            $data['tgBatDau'] ?? null,
            $data['tgKetThuc'] ?? null,
            $iconHienThi,
            $id
        );

        if ($stmt->execute()) {
            echo json_encode(["message" => "Cập nhật thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Lỗi khi cập nhật"]);
        }
    }

    // Xóa hộp thoại
    public function destroy($id)
    {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID không hợp lệ"]);
            return;
        }

        // Lấy hộp thoại cần xóa
        $sql = "SELECT * FROM hopthoai WHERE maHT = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $hopThoai = $result->fetch_assoc();

        if (!$hopThoai) {
            http_response_code(404);
            echo json_encode(["error" => "Không tìm thấy hộp thoại"]);
            return;
        }

        // Xóa file icon nếu có
        if ($hopThoai['iconHienThi'] && file_exists($hopThoai['iconHienThi'])) {
            unlink($hopThoai['iconHienThi']);
        }

        // Xóa hộp thoại
        $sql = "DELETE FROM hopthoai WHERE maHT = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Xóa hộp thoại thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Lỗi khi xóa"]);
        }
    }
}
