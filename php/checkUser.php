<?php
session_start();
$id;
$output;

if (isset($_POST['destroySession'])) {
    session_destroy();
    $output['logedIn'] = false;
}

if ($output['logedIn'] = isset($_SESSION['id'])) {
    include 'connection.php';
    $id = $_SESSION['id'];

    if ($connect->connect_error) {
        die("Datenbank connection failed" . $connect->connect_error);
    }

    $name = $connect->query("SELECT name FROM konto WHERE id = '$id'");
    if ($name->num_rows > 0) {
        $output['name'] = $name->fetch_assoc()['name'];
    }
    $connect->close();
}
echo json_encode($output);
