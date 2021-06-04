<?php
include 'connection.php';

function output($suc, $msgs)
{
    echo json_encode(['success' => $suc, 'msg' => $msgs]);
    if (isset($GLOBALS['connect'])) {
        $GLOBALS['connect']->close();
    }
    exit;
}

if (isset($_POST['logInData'])) {
    $logInData = json_decode($_POST['logInData']);
    $logInData->name = $connect->real_escape_string($logInData->name);
    $logInData->pass = $connect->real_escape_string($logInData->pass);
    $output = [];

    if (strlen($logInData->name) < 3) {
        $output[] = 'Der Name muss mindestens 3 Zeichen haben!';
    }

    if (strlen($logInData->pass) < 4) {
        $output[] = 'Der Passwort muss mindestens 5 Zeichen haben!';
    }

    if (count($output) > 0) {
        output(false, $output);
    }

    $sql = "SELECT * FROM konto WHERE name = '$logInData->name'";
    $res = $connect->query($sql);

    if ($res->num_rows > 0) {

        $res = $res->fetch_assoc();
        if ($res['password'] == md5('Videos' .  $logInData->pass)) {
            session_start();
            $_SESSION['id'] = $res['id'];
            output(true, ['']);
        } else {
            output(false, 'Passwort ist falsch!');
        }

        $output = json_encode($output);
        echo $output;
    } else {
        output(false, [$logInData->name . ' gibt es nicht!']);
    }
}

// if (isset($_POST['checkId'])) {
//     $id = $_POST['checkId'];
//     $output;

//     $res = $connect->query("SELECT name FROM konto where id = '$id'");
//     if ($res->num_rows > 0) {
//         $res = $res->fetch_assoc();
//         $output = ['message' => true, 'name' => $res['name']];
//     }
//     if (!isset($output)) {
//         $output = ['message' => false];
//     }
//     echo json_encode($output);
// }

$connect->close();
