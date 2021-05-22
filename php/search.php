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
$found = $connect->query("SELECT k.name, p.pname FROM konto k join profilandcoverpic p using(id) WHERE LOWER(k.name) LIKE '%$sStr%'");

if ($found->num_rows > 0) {
    while ($row = $found->fetch_assoc()) {
        $output['channels'][] = ['name' => $row['name'], 'pname' => $row['pname']];
    }
}

$found = $connect->query("SELECT id, title, poster FROM videos WHERE LOWER(title) LIKE '%$sStr%'");

if ($found->num_rows > 0) {
    while ($row = $found->fetch_assoc()) {
        $output['videos'][] = ['id' => $row['id'], 'title' => $row['title'], 'poster' => $row['poster']];
    }
}

echo json_encode($output);
$connect->close();