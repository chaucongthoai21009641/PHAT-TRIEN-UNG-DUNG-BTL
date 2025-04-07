<?php
require_once "../config/database.php"; // Nhúng file Database.php

$db = new Database();  // Tạo một instance của class Database
$conn = $db->connect(); // Gọi phương thức connect để kết nối

$conn->set_charset("utf8"); // Thiết lập charset cho kết nối


// Xử lý request
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":
        if (isset($_GET['maCL'])) {
            getChiTietCaLam($conn, $_GET['maCL']);
        } else {
            getAllCaLam($conn);
        }
        break;
    case "POST":
        addCaLam($conn);
        break;
    case "PUT":
        parse_str(file_get_contents("php://input"), $_PUT);
        updateCaLam($conn, $_PUT);
        break;
    case "DELETE":
        parse_str(file_get_contents("php://input"), $_DELETE);
        deleteCaLam($conn, $_DELETE['maCL']);
        break;
    default:
        echo json_encode(["message" => "Phương thức không hỗ trợ"]);
}

/**
 * Lấy danh sách tất cả ca làm
 */
function getAllCaLam($conn)
{
    $sql = "SELECT * FROM calam";
    $result = $conn->query($sql);
    $caLamList = [];

    while ($row = $result->fetch_assoc()) {
        $caLamList[] = $row;
    }

    echo json_encode($caLamList);
}

/**
 * Lấy chi tiết ca làm theo maCL
 */
function getChiTietCaLam($conn, $maCL)
{
    $maCL = intval($maCL);
    $sql = "SELECT * FROM calam WHERE maCL = $maCL";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["message" => "Không tìm thấy ca làm"]);
    }
}

/**
 * Thêm ca làm mới
 */
function addCaLam($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['tenCa'], $data['gioCheckInSom'], $data['gioCheckOutMuon'])) {
        echo json_encode(["message" => "Thiếu dữ liệu"]);
        return;
    }

    $tenCa = $conn->real_escape_string($data['tenCa']);
    $gioCheckInSom = intval($data['gioCheckInSom']);
    $gioCheckOutMuon = intval($data['gioCheckOutMuon']);

    $sql = "INSERT INTO calam (tenCa, gioCheckInSom, gioCheckOutMuon) VALUES ('$tenCa', $gioCheckInSom, $gioCheckOutMuon)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Thêm ca làm thành công"]);
    } else {
        echo json_encode(["message" => "Lỗi: " . $conn->error]);
    }
}

/**
 * Cập nhật ca làm
 */
function updateCaLam($conn, $data)
{
    if (!isset($data['maCL'], $data['tenCa'], $data['gioCheckInSom'], $data['gioCheckOutMuon'])) {
        echo json_encode(["message" => "Thiếu dữ liệu"]);
        return;
    }

    $maCL = intval($data['maCL']);
    $tenCa = $conn->real_escape_string($data['tenCa']);
    $gioCheckInSom = intval($data['gioCheckInSom']);
    $gioCheckOutMuon = intval($data['gioCheckOutMuon']);

    $sql = "UPDATE calam SET tenCa='$tenCa', gioCheckInSom=$gioCheckInSom, gioCheckOutMuon=$gioCheckOutMuon WHERE maCL=$maCL";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Cập nhật thành công"]);
    } else {
        echo json_encode(["message" => "Lỗi: " . $conn->error]);
    }
}

/**
 * Xóa ca làm
 */
function deleteCaLam($conn, $maCL)
{
    $maCL = intval($maCL);

    $sql = "DELETE FROM calam WHERE maCL=$maCL";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Xóa thành công"]);
    } else {
        echo json_encode(["message" => "Lỗi: " . $conn->error]);
    }
}

// Đóng kết nối
$conn->close();
