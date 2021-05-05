<?php
$db_host = 'localhost';
$db_datenbank = 'video-projekt';
$db_username = 'video-projekt';
$db_password = 'passw';

$connect = new mysqli($db_host, $db_username, $db_password, $db_datenbank);

if ($connect->connect_error) {
    die("Datenbank connection failed" . $connect->connect_error);
}

if (isset($_POST['logInData'])) {
    $logInData = json_decode($_POST['logInData']);
    $logInData->name = $connect->real_escape_string($logInData->name);
    $logInData->pass = $connect->real_escape_string($logInData->pass);

    if (strlen($logInData->name) >= 3 && strlen($logInData->pass) > 4) {
        $sql = "SELECT * FROM konto WHERE name = '$logInData->name'";
        $res = $connect->query($sql);

        if ($res->num_rows > 0) {
            $output;

            $res = $res->fetch_assoc();
            if ($res['password'] == md5('Videos' .  $logInData->pass)) {
                $output = ['news' => true, 'message' => $res['id']];
                session_start();
                $_SESSION['id'] = $res['id'];
            } else {
                $output = ['news' => false, 'message' => 'Passwort ist falsch!'];
            }

            $output = json_encode($output);
            echo $output;
        } else {
            echo json_encode(['news' => false, 'message' => $logInData->name . ' gibt es nicht!']);
        }
    }
}

if (isset($_POST['checkId'])) {
    $id = $_POST['checkId'];
    $output;

    $res = $connect->query("SELECT name FROM konto where id = $id");
        if ($res->num_rows > 0) {
            $res = $res->fetch_assoc();
            $output = ['message' => true, 'name' => $res['name']];
        }
    if (!isset($output)) {
        $output = ['message' => false];
    }
    echo json_encode($output);
}

$connect->close();