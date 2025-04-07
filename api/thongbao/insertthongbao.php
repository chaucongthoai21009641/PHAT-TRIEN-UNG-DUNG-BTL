<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/database.php';
include_once '../../models/thongbao.php';  // Đảm bảo bạn đã có model ThongBao

$db = new Database();
$conn = $db->connect();

// Lấy dữ liệu từ request (POST)
$tieuDe = $_POST['tieuDe'] ?? null;
$url = $_POST['url'] ?? null;
$tgBatDau = $_POST['tgBatDau'] ?? null;
$tgKetThuc = $_POST['tgKetThuc'] ?? null;

// Kiểm tra dữ liệu đầu vào
if ($tieuDe && $url && $tgBatDau && $tgKetThuc) {
    $thongbao = new ThongBao($conn);

    // Câu lệnh SQL để thêm thông báo
    $query = "INSERT INTO thongbao (tieuDe, url, tgBatDau, tgKetThuc) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    // Ràng buộc tham số
    $stmt->bind_param("ssss", $tieuDe, $url, $tgBatDau, $tgKetThuc); // "ssss" là kiểu dữ liệu của các tham số

    // Thực thi câu lệnh thông qua phương thức insertUpDel trong model
    if ($thongbao->insertUpDel($stmt)) {
        // Nếu thành công
        echo json_encode(["message" => "Tạo thông báo thành công."]);
    } else {
        // Nếu thất bại
        echo json_encode(["error" => "Tạo thông báo thất bại.", "error_details" => $stmt->error]);
    }

    // Đóng statement
    $stmt->close();
} else {
    // Trả về lỗi nếu thiếu dữ liệu
    echo json_encode(["error" => "Thiếu dữ liệu. Vui lòng kiểm tra lại các trường tiêu đề, URL và thời gian."]);
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
