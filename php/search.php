<?php
$db_host = 'localhost';
$db_datenbank = 'video-projekt';
$db_username = 'video-projekt';
$db_password = 'passw';

$connect = new mysqli($db_host, $db_username, $db_password, $db_datenbank);

if ($connect->connect_error) {
    die("Datenbank connection failed" . $connect->connect_error);
}

$searchData = $_POST['searchData'];
$output = [];
$sStr = strtolower($searchData['sStr']);
$found = $connect->query("SELECT name FROM konto WHERE LOWER(name) LIKE '%$sStr%'");

if ($found->num_rows > 0) {
    while ($row = $found->fetch_assoc()) {
        $output['channels'][] = $row['name'];
    }
}

$found = $connect->query("SELECT name FROM videoinfos WHERE LOWER(name) LIKE '%$sStr%'");

if ($found->num_rows > 0) {
    while ($row = $found->fetch_assoc()) {
        $output['videos'][] = $row['name'];
    }
}

echo json_encode($output);
$connect->close();