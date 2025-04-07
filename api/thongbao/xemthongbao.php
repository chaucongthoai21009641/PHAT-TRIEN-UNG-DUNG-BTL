<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Kết nối đến cơ sở dữ liệu
include_once '../../config/database.php';
include_once '../../models/thongbao.php';

$db = new Database();
$conn = $db->connect();
$thongbao = new ThongBao($conn);

// Kiểm tra nếu có tham số 'maTB' trong URL
if (isset($_GET['maTB'])) {
    $maTB = $_GET['maTB'];

    // Gọi phương thức selectOne từ model ThongBao để lấy thông báo
    $result = $thongbao->selectOne($maTB);

    // Kiểm tra nếu có thông báo tìm thấy
    if ($result->num_rows > 0) {
        $thongBao = $result->fetch_assoc(); // Lấy thông báo dưới dạng mảng

        // Trả về thông báo dưới dạng JSON
        echo json_encode($thongBao);
    } else {
        echo json_encode(["error" => "Thông báo không tồn tại"]);
    }
} else {
    echo json_encode(["error" => "ID thông báo không được cung cấp"]);
}

$conn->close();
