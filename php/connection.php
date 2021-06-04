<?php
$db_host = 'localhost';
$db_datenbank = 'video-projekt';
$db_username = 'video-projekt';
$db_password = 'passw';

$connect = new mysqli($db_host, $db_username, $db_password, $db_datenbank);

if ($connect->connect_error) {
    die("Datenbank connection failed" . $connect->connect_error);
}