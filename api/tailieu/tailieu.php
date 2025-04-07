<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once '../../config/database.php'; // Import file database
include_once '../../models/tailieu.php'; // Import file database

$db = new Database();
$conn = $db->connect();

$tailieu = new tailieu($conn);
$getAll = $tailieu->selectAll();
$num = $getAll->num_rows;

if ($num > 0) {
    $tailieu_arr = array();

    while ($row = $getAll->fetch_assoc()) {
        extract($row);
        $tailieu_item = array(
            "maTL" => $maTL,
            "tieuDe" => $tieuDe,
            "url" => $url,
            "tgBatDau" => $tgBatDau,
            "tgKetThuc" => $tgKetThuc
        );

        array_push($tailieu_arr, $tailieu_item);
    }

    echo json_encode($tailieu_arr);
}
