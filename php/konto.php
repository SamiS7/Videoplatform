<?php
function output($suc, $str, $f = null)
{

    if (!$suc) {
        echo json_encode(['success' => false, 'output' => $str]);
    } else {
        echo json_encode(['success' => true, 'output' => $str, 'fileName' => $f]);
    }
    $GLOBALS['connection']->close();
    exit;
}

session_start();
if (!isset($_SESSION['id'])) {
    output(false, 'Sie sind nicht angemeldet!');
}

$db_host = 'localhost';
$db_datenbank = 'video-projekt';
$db_username = 'video-projekt';
$db_password = 'passw';
$connect = new mysqli($db_host, $db_username, $db_password, $db_datenbank);

if ($connect->connect_error) {
    die("Datenbank connection failed" . $connect->connect_error);
}

$profArrName = 'profileFile';
if (isset($_FILES[$profArrName])) {
    $file = $_FILES[$profArrName];
    $sentImg = $_FILES[$profArrName]['tmp_name'];
    $dir = '../img/profileImg/';
    $imgType = pathinfo($_FILES[$profArrName]['name'], PATHINFO_EXTENSION);
    $imgType = strtolower($imgType);
    $supportedTypes = ['jpg', 'jpeg', 'png'];

    if (!getimagesize($sentImg)) {
        output(false, 'Die geschickte Datei ist kein Bild!');
    }
    if (!in_array($imgType, $supportedTypes)) {
        output(false, 'Profilbild muss JPG, JPEG oder PNG sein!');
    }

    $tSize = getimagesize($sentImg);
    $result = $tSize[0] / $tSize[1];
    if ($result < 0.9 || $result > 1.1) {
        output(false, 'Das Profilbild muss im 1:1 Format sein!');
    }

    if ($_FILES[$profArrName]['size'] > 400000) {
        output(false, 'Profilbild darf nicht größer sein als 400 KB!');
    }

    $usrName;
    $id = $_SESSION['id'];
    $res = $connect->query("SELECT name FROM konto WHERE id = '$id'");
    if ($res->num_rows > 0) {
        $res = $res->fetch_assoc();
        $usrName = $res['name'];
        $fileName = $usrName . 'ProfImg.' . $imgType;
        $changeStatement;
        if ($connect->query("SELECT * FROM profilandcoverpic WHERE id = '$id'")->num_rows > 0) {
            $changeStatement = "UPDATE profilandcoverpic SET pName = '$fileName' WHERE id = '$id'";
        } else {
            $changeStatement = "INSERT INTO profilandcoverpic VALUE('$id', '$fileName', '')";
        }

        if (move_uploaded_file($sentImg, $dir . $fileName) && $connect->query($changeStatement)) {
            output(true, 'Ihr Profilbild wurde geändert!', $fileName);
        } else {
            output(false, 'Irgendwas ist schiefgelaufen, versuchen Sie später nochmal.');
        }
    } else {
        output(false, 'Irgendwas ist schiefgelaufen, versuchen Sie später nochmal.');
    }
}

$coverArrName = 'coverFile';
if (isset($_FILES[$coverArrName])) {
    $file = $_FILES[$coverArrName];
    $sentImg = $_FILES[$coverArrName]['tmp_name'];
    $dir = '../img/coverImg/';
    $imgType = pathinfo($_FILES[$coverArrName]['name'], PATHINFO_EXTENSION);
    $imgType = strtolower($imgType);
    $supportedTypes = ['jpg', 'jpeg', 'png'];

    if (!getimagesize($sentImg)) {
        output(false, 'Die geschickte Datei ist kein Bild!');
    }
    if (!in_array($imgType, $supportedTypes)) {
        output(false, 'Coverbild muss JPG, JPEG oder PNG sein!');
    }
    $tSize = getimagesize($sentImg);
    $result = $tSize[0] / $tSize[1];
    if ($result < 3 || $result > 4) {
        output(false, 'Das Thumbnail muss im 15:4 Format sein!');
    }
    if ($_FILES[$coverArrName]['size'] > 700000) {
        output(false, 'Coverbild darf nicht größer sein als 700 KB!');
    }

    $usrName;
    $id = $_SESSION['id'];
    $res = $connect->query("SELECT name FROM konto WHERE id = '$id'");
    if ($res->num_rows > 0) {
        $res = $res->fetch_assoc();
        $usrName = $res['name'];
        $fileName = $usrName . 'CoverImg.' . $imgType;
        $changeStatement;
        if ($connect->query("SELECT * FROM profilandcoverpic WHERE id = '$id'")->num_rows > 0) {
            $changeStatement = "UPDATE profilandcoverpic SET cName = '$fileName' WHERE id = '$id'";
        } else {
            $changeStatement = "INSERT INTO profilandcoverpic VALUE('$id', '', '$fileName')";
        }

        if (move_uploaded_file($sentImg, $dir . $fileName) && $connect->query($changeStatement)) {
            output(true, 'Ihr Coverbild wurde geändert!', $fileName);
        } else {
            output(false, 'Irgendwas ist schiefgelaufen, versuchen Sie später nochmal.');
        }
    } else {
        output(false, 'Irgendwas ist schiefgelaufen, versuchen Sie später nochmal.');
    }
}

if (isset($_FILES['video']) && isset($_FILES['thumb'])) {
    $video = $_FILES['video'];
    $thumb = $_FILES['thumb'];
    $title = $connect->real_escape_string($_POST['title']);
    $cat = $connect->real_escape_string($_POST['cat']);
    $tags = $connect->real_escape_string($_POST['tags']);
    $vType = pathinfo($video['name'], PATHINFO_EXTENSION);
    $tType = pathinfo($thumb['name'], PATHINFO_EXTENSION);

    if (!in_array($vType, ['mp4', 'mov', 'avi', 'webm'])) {
        output(false, 'Thumbnail muss mp4, mov, avi oder webm sein!');
    }
    if (!getimagesize($thumb['tmp_name'])) {
        output(false, 'Die geschickte Datei ist kein Bild!');
    }
    if (!in_array($tType, ['jpg', 'jpeg', 'png'])) {
        output(false, 'Thumbnail muss JPG, JPEG oder PNG sein!');
    }
    $tSize = getimagesize($thumb['tmp_name']);
    $result = $tSize[0] / $tSize[1];
    if ($result < 1.77 || $result > 1.78) {
        output(false, 'Das Thumbnail muss im 16:9 Format sein!');
    }
    if ($video['size'] > 240000000) {
        output(false, 'Das Video darf nicht größer sein als 30 MB!');
    }
    if ($thumb['size'] > 400000) {
        output(false, 'Thumbnail darf nicht größer sein als 400 KB!');
    }

    $id = $_SESSION['id'];

    if ($res = $connect->query("SELECT name FROM konto where id = '$id'")) {
        $usrName = $res->fetch_assoc()['name'];
        $videoCount = $connect->query("SELECT * FROM Videos WHERE owner = '$id'")->num_rows;
        $videoCount += 10;
        while ($connect->query("SELECT * FROM Videos WHERE id = '$videoCount'")->num_rows > 0) {
            $videoCount++;
        }
        $insertStatement = "INSERT INTO videos VALUE('$videoCount.$vType', null, '$id', '$title', 0, '$cat', '$tags', '$videoCount.$tType', NOW())";
        if (
            $connect->query($insertStatement) && move_uploaded_file($video['tmp_name'], "../videos/$videoCount.$vType") && move_uploaded_file($thumb['tmp_name'], "../img/thumb/$videoCount.$tType")
        ) {
            output(true, '');
        } else {
            output(false, 'Irgendwas ist schiefgelaufen, versuchen Sie später nochmal.');
        }
    } else {
        output(false, 'Sie sind nicht angemeldet!');
    }
}

if (isset($_POST['myVideos'])) {
    $id = $_SESSION['id'];
    $res = $connect->query("SELECT id, title, poster FROM videos WHERE owner = '$id'");
    $data = [];
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }
    output(true, $data);
}
