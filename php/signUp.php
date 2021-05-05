<?php
$db_host = 'localhost';
$db_datenbank = 'video-projekt';
$db_username = 'video-projekt';
$db_password = 'passw';

$connect = new mysqli($db_host, $db_username, $db_password, $db_datenbank);

if ($connect->connect_error) {
    die("Datenbank connection failed" . $connect->connect_error);
}

if (isset($_POST['signUpData'])) {
    $signUpData = json_decode($_POST['signUpData']);
    $signUpData->name = $connect->real_escape_string($signUpData->name);
    $signUpData->pass1 = $connect->real_escape_string($signUpData->pass1);
    $signUpData->pass2 = $connect->real_escape_string($signUpData->pass2);
    $signUpData->email = $connect->real_escape_string($signUpData->email);

    $now = new DateTime();
    $bDay = new DateTime($signUpData->bDay);
    $diff = $now->getTimestamp() - $bDay->getTimestamp();
    $output;
    $ePat = "/^[A-zäöüßÄÖÜ\.\_\-\d]{2,40}@([A-z\-\_]+\.){1,5}[A-z]{2,10}$/i";

    if (strlen($signUpData->name) >= 3 && strlen($signUpData->name) <= 25 && $diff > 0 && strlen($signUpData->country) > 3 && strlen($signUpData->country) <= 30 && strlen($signUpData->pass1) > 4 && $signUpData->pass1 == $signUpData->pass2 && preg_match($ePat, $signUpData->email)) {
        $sql = "select name from konto where name = '$signUpData->name'";
        $db_data =
            $data = new stdClass();
        if ($connect->query($sql)->num_rows <= 0) {
            $output = creatAccount($signUpData, $connect);
        } else {
            $output = ['news' => false, 'message' => $signUpData->name . ' existiert schon!'];
        }
    } else if ($signUpData->pass1 != $signUpData->pass2) {
        $output = ['news' => false, 'message' => 'Passwörter stimmen nicht überein!'];
    } else if ($diff <= 0) {
        $output = ['news' => false, 'message' => 'Ihr Geburtsdatum stimmt nicht!'];
    } else {

    }

    echo json_encode($output);
}

function fKey($name)
{
    $fKey = 0;
    foreach (unpack('C*', $name) as $x) {
        $fKey += $x;
    }
    return $fKey * strlen($name) * strlen($name);
}
function creatAccount($sData, $connect)
{
    unset($sData->pass2);
    $sData->creatingDate = date('Y-m-d');
    $sData->abos = 0;
    $key = fKey($sData->name . $sData->pass1);

    if ($connect->query("SELECT id from konto where id = '$key'")->num_rows > 0) {
        $key = $sData->name . '-' . $key;
    }

    $sData->id = $key;
    $sData->pass1 = MD5('Videos' . $sData->pass1);
    $insertStatement = "INSERT INTO `konto` VALUES('$sData->name', '$sData->bDay', '$sData->country', '$sData->pass1', '$sData->creatingDate', $sData->abos, '$sData->id', '$sData->email')";
    if ($connect->query($insertStatement)) {
        session_start();
        $_SESSION['id'] = $key;
        return ['news' => true, 'message' => $key];
    }
    return ['news' => false, 'message' => 'Sie konnten nicht registriest werden! Irgendetwas stimmte nicht.'];
}

$connect->close();