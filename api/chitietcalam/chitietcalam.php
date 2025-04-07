<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once '../../config/database.php'; // Import file database
include_once '../../models/chitietcalam.php'; // Import file database

$db = new Database();
$conn = $db->connect();

$chitietcalam = new chitietcalam($conn);
$getAll = $chitietcalam->selectAll();
$num = $getAll->num_rows;

if ($num > 0) {
    $chitietcalam_arr = array();

    while ($row = $getAll->fetch_assoc()) {
        extract($row);
        $chitietcalam_item = array(
            "maCTCL" => $maCTCL,
            "thuTrongTuan" => $thuTrongTuan,
            "tgBatDau" => $tgBatDau,
            "tgKetThuc" => $tgKetThuc,
            "tgBatDauNghi" => $tgBatDauNghi,
            "tgKetThucNghi" => $tgKetThucNghi,
            "heSoLuong" => $heSoLuong,
            "tienThuong" => $tienThuong,
            "maCL" => $maCL
        );

        array_push($chitietcalam_arr, $chitietcalam_item);
    }

    echo json_encode($chitietcalam_arr);
}
