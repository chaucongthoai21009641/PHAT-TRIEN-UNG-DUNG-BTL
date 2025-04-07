<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once '../../config/database.php'; // Import file database
include_once '../../models/nhanvien.php'; // Import file database

$db = new Database();
$conn = $db->connect();

$nhanvien = new NhanVien($conn);
$getAll = $nhanvien->selectAll();
$num = $getAll->num_rows;

if ($num > 0) {
    $nhanvien_arr = array();

    while ($row = $getAll->fetch_assoc()) {
        extract($row);
        $nhanvien_item = array(
            "maNV" => $maNV,
            "hoTen" => $hoTen,
            "chucDanh" => $chucDanh,
            "soDienThoai" => $soDienThoai,
            "email" => $email,
            "gioiTinh" => $gioiTinh,
            "ngayVaoLam" => $ngayVaoLam,
            "tienLuong" => $tienLuong,
            "ngaySinh" => $ngaySinh,
            "trangThai" => $trangThai,
            "tai_khoan" => array(
                "maTK" => $maTK,
                "matKhau"=>$matKhau,
                "tenTaiKhoan" => $tenTaiKhoan,
                "quyenHan" => $quyenHan,
            )
        );

        array_push($nhanvien_arr, $nhanvien_item);
    }

    echo json_encode($nhanvien_arr);
}
