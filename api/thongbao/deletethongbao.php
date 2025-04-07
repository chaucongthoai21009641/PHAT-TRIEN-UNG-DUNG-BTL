<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/database.php';
include_once '../../models/thongbao.php';

$db = new Database();
$conn = $db->connect();

// Lấy dữ liệu từ request
$maTB = $_POST['maTB'] ?? null;

if ($maTB) {
    $thongbao = new ThongBao($conn);

    // Câu lệnh DELETE để xóa bản ghi
    $query = "DELETE FROM thongbao WHERE maTB = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $maTB);  // Liên kết tham số maTB

    if ($stmt->execute()) {
        echo json_encode(["message" => "Xóa thông báo thành công."]);
    } else {
        echo json_encode(["error" => "Xóa thông báo thất bại.", "error_details" => $stmt->error]);
    }

    // Đóng kết nối
    $stmt->close();
} else {
    echo json_encode(["error" => "Thiếu dữ liệu maTB."]);
}

$conn->close();
