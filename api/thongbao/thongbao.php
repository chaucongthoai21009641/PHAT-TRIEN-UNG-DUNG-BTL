<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once '../../config/database.php'; // Import file database
include_once '../../models/thongbao.php'; // Import file database

$db = new Database();
$conn = $db->connect();

$thongbao = new thongbao($conn);
$getAll = $thongbao->selectAll();
$num = $getAll->num_rows;

if ($num > 0) {
    $thongbao_arr = array();

    while ($row = $getAll->fetch_assoc()) {
        extract($row);
        $thongbao_item = array(
            "maTB" => $maTB,
            "tieuDe" => $tieuDe,
            "url" => $url,
            "tgBatDau" => $tgBatDau,
            "tgKetThuc" => $tgKetThuc
        );

        array_push($thongbao_arr, $thongbao_item);
    }

    echo json_encode($thongbao_arr);
}
