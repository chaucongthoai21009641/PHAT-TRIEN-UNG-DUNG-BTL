<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/database.php';
include_once '../../models/taikhoan.php';
include_once '../../models/nhanvien.php';

$db = new Database();
$conn = $db->connect();

// Lấy dữ liệu từ request
$tenTaiKhoan = $_POST['tenTaiKhoan'] ?? null;
$matKhau = $_POST['matKhau'] ?? null;
$hoTen = $_POST['hoTen'] ?? null;
$chucDanh = $_POST['chucDanh'] ?? null;
$soDienThoai = $_POST['soDienThoai'] ?? null;
$email = $_POST['email'] ?? null;
$gioiTinh = $_POST['gioiTinh'] ?? null;
$tienLuong = $_POST['tienLuong'] ?? 0;
$ngaySinh = $_POST['ngaySinh'] ?? null;
$trangThai = $_POST['trangThai'] ?? 1;

if ($tenTaiKhoan && $matKhau) {
    $taikhoan = new TaiKhoan($conn);
    $nhanvien = new NhanVien($conn);

    // 1. Thêm vào bảng tài khoản
    $query1 = "INSERT INTO taikhoan (tenTaiKhoan, matKhau) VALUES (?, ?)";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bind_param("ss", $tenTaiKhoan, $matKhau);

    if ($taikhoan->insertUpDel($stmt1) === 1) {
        // 2. Lấy maTK của tài khoản vừa tạo
        $maTK = $conn->insert_id;  // Lấy ID của bản ghi vừa được thêm vào

        // 3. Thêm vào bảng nhân viên với các cột khác như hoTen, chucDanh, soDienThoai, email, gioiTinh, tienLuong, ngaySinh, trangThai
        $query2 = "INSERT INTO nhanvien 
                    (maTK, hoTen, chucDanh, soDienThoai, email, gioiTinh, ngayVaoLam, tienLuong, ngaySinh, trangThai, maLuong) 
                    VALUES (?, ?, ?, ?, ?, ?, CURDATE(), ?, ?, ?, 1)";  // maLuong mặc định là 1

        $stmt2 = $conn->prepare($query2);
        $stmt2->bind_param("issssssds", $maTK, $hoTen, $chucDanh, $soDienThoai, $email, $gioiTinh, $tienLuong, $ngaySinh, $trangThai);

        if ($stmt2->execute()) {
            echo json_encode(["message" => "Tạo tài khoản và nhân viên thành công."]);
        } else {
            echo json_encode(["warning" => "Tạo tài khoản thành công, nhưng thêm nhân viên thất bại.", "error" => $stmt2->error]);
        }
    } else {
        echo json_encode(["error" => "Tạo tài khoản thất bại.", "error_details" => $stmt1->error]);
    }
} else {
    echo json_encode(["error" => "Thiếu dữ liệu."]);
}

$conn->close();
