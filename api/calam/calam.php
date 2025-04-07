<?php
header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json');

include_once '../../config/database.php'; // Import file database
include_once '../../models/calam.php'; // Import file database

$db = new Database();
$conn = $db->connect();

$calam = new calam($conn);
$getAll = $calam->selectAll();
$num = $getAll->num_rows;

if ($num > 0) {
    $calam_arr = array();

    while ($row = $getAll->fetch_assoc()) {
        extract($row);
        $calam_item = array(
            "maCL" => $maCL,
            "tenCa" => $tenCa,
            "gioCheckInSom" => $gioCheckInSom,
            "gioCheckOutMuon" => $gioCheckOutMuon
        );

        array_push($calam_arr, $calam_item);
    }

    echo json_encode($calam_arr);
}
