<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/database.php';
include_once '../../models/hopthoai.php';

$db = new Database();
$conn = $db->connect();

// Lấy dữ liệu từ request
$maHT = $_POST['maHT'] ?? null;

if ($maHT) {
    $hopthoai = new HopThoai($conn);

    // Câu lệnh DELETE để xóa bản ghi
    $query = "DELETE FROM hopthoai WHERE maHT = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $maHT);  // Liên kết tham số maHT

    if ($stmt->execute()) {
        echo json_encode(["message" => "Xóa hộp thoại thành công."]);
    } else {
        echo json_encode(["error" => "Xóa hộp thoại thất bại.", "error_details" => $stmt->error]);
    }

    // Đóng kết nối
    $stmt->close();
} else {
    echo json_encode(["error" => "Thiếu dữ liệu maHT."]);
}

$conn->close();
