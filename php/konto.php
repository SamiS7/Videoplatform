<?php
function output($suc, $msgs)
{
    echo json_encode(['success' => $suc, 'msg' => $msgs]);
    if (isset($GLOBALS['connect'])) {
        $GLOBALS['connect']->close();
    }
    exit;
}

session_start();
if (!isset($_SESSION['id'])) {
    output(false, ['Sie sind nicht angemeldet!']);
}
$id = $_SESSION['id'];

include 'connection.php';

$profArrName = 'profileFile';
if (isset($_FILES[$profArrName])) {
    $file = $_FILES[$profArrName];
    $sentImg = $_FILES[$profArrName]['tmp_name'];
    $dir = '../img/profileImg/';
    $imgType = pathinfo($_FILES[$profArrName]['name'], PATHINFO_EXTENSION);
    $imgType = strtolower($imgType);
    $supportedTypes = ['jpg', 'jpeg', 'png'];
    $output = [];

    $x = getimagesize($sentImg);
    if (!getimagesize($sentImg)) {
        $output[] = 'Die geschickte Datei ist kein Bild!';
        output(false, $output);
    }
    if (!in_array($imgType, $supportedTypes)) {
        $output[] = 'Profilbild muss JPG, JPEG oder PNG sein!';
        output(false, $output);
    }

    $tSize = getimagesize($sentImg);
    $result = $tSize[0] / $tSize[1];
    if ($result < 0.9 || $result > 1.1) {
        $output[] = 'Das Profilbild muss im 1:1 Format sein!';
    }

    if ($_FILES[$profArrName]['size'] > 400000) {
        $Output[] = 'Profilbild darf nicht größer sein als 400 KB!';
    }

    if (count($output) > 0) {
        output(false, $output);
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

        if ($connect->query($changeStatement) && move_uploaded_file($sentImg, $dir . $fileName)) {
            output(true, ['Ihr Profilbild wurde geändert!'], $fileName);
        } else {
            $connect->rollback();
            output(false, ['Irgendwas ist schiefgelaufen, versuchen Sie später nochmal.']);
        }
    } else {
        output(false, ['Irgendwas ist schiefgelaufen, versuchen Sie später nochmal.']);
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
    $output = [];

    if (!getimagesize($sentImg)) {
        $output[] = 'Die geschickte Datei ist kein Bild!';
        output(false, $output);
    }
    if (!in_array($imgType, $supportedTypes)) {
        $output[] = 'Coverbild muss JPG, JPEG oder PNG sein!';
        output(false, $output);
    }
    $tSize = getimagesize($sentImg);
    $result = $tSize[0] / $tSize[1];
    if ($result < 3 || $result > 4) {
        $output[] = 'Das Thumbnail muss im 15:4 Format sein!';
    }
    if ($_FILES[$coverArrName]['size'] > 700000) {
        $output[] = 'Coverbild darf nicht größer sein als 700 KB!';
    }

    if (count($output) > 0) {
        output(false, $output);
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

        if ($connect->query($changeStatement) && move_uploaded_file($sentImg, $dir . $fileName)) {
            output(true, ['Ihr Coverbild wurde geändert!'], $fileName);
        } else {
            $connect->rollback();
            output(false, ['Irgendwas ist schiefgelaufen, versuchen Sie später nochmal.']);
        }
    } else {
        output(false, ['Irgendwas ist schiefgelaufen, versuchen Sie später nochmal.']);
    }
}

if (isset($_FILES['video']) && isset($_FILES['thumb'])) {
    function asArrStr($arr) {
        $str = '[';
        for ($i = 0; $i < count($arr) - 1; $i++) {
            $str .= '"' . ($arr[$i] . '", ');
        }
        $str .= '"' . $arr[count($arr) - 1] . '"]';
        return $str;
    }

    $video = $_FILES['video'];
    $thumb = $_FILES['thumb'];
    $title = $connect->real_escape_string($_POST['title']);
    $cat = $connect->real_escape_string($_POST['cat']);
    $cat = asArrStr(explode(';', $cat));
    $tags = $connect->real_escape_string($_POST['tags']);
    $tags = asArrStr(explode(';', $tags));
    $vType = pathinfo($video['name'], PATHINFO_EXTENSION);
    $tType = pathinfo($thumb['name'], PATHINFO_EXTENSION);
    $output = [];

    if (!in_array($vType, ['mp4', 'mov', 'avi', 'webm'])) {
        $output[] = 'Thumbnail muss mp4, mov, avi oder webm sein!';
        output(false, $output);
    }
    if (!getimagesize($thumb['tmp_name'])) {
        $output[] = 'Die geschickte Datei ist kein Video!';
        output(false, $output);
    }
    if (!in_array($tType, ['jpg', 'jpeg', 'png'])) {
        $output[] = 'Thumbnail muss JPG, JPEG oder PNG sein!';
    }
    $tSize = getimagesize($thumb['tmp_name']);
    $result = $tSize[0] / $tSize[1];
    if ($result < 1.77 || $result > 1.78) {
        $output[] = 'Das Thumbnail muss im 16:9 Format sein!';
    }
    if ($video['size'] > 240000000) {
        $output[] = 'Das Video darf nicht größer sein als 30 MB!';
    }
    if ($thumb['size'] > 2000000) {
        $output[] = 'Thumbnail darf nicht größer sein als 2 MB!';
    }

    if (count($output) > 0) {
        output(false, $output);
    }

    $id = $_SESSION['id'];

    if ($res = $connect->query("SELECT name FROM konto where id = '$id'")) {
        $usrName = $res->fetch_assoc()['name'];
        $videoCount = $connect->query("SELECT id FROM Videos")->num_rows;
        $videoCount += 10;
        while ($connect->query("SELECT * FROM Videos WHERE name + '.$vType' = '$videoCount.$vType'")->num_rows > 0) {
            $videoCount++;
        }
        $connect->autocommit(false);
        $insertStatement = "INSERT INTO videos VALUE('$videoCount.$vType', null, '$id', '$title', 0, '$cat', '$tags', '$videoCount.$tType', NOW(), 0)";
        if (
            $connect->query($insertStatement) &&
            move_uploaded_file($video['tmp_name'], "../videos/$videoCount.$vType") &&
            move_uploaded_file($thumb['tmp_name'], "../img/thumb/$videoCount.$tType")
        ) {
            $connect->query("INSERT INTO dailydata (video) values(LAST_INSERT_ID())");
            $connect->commit();
            output(true, ['Ihr Video wurde erfolgreich hochgeladen!']);
        } else {
            $connect->rollback();
            output(false, ['Irgendwas ist schiefgelaufen, versuchen Sie später nochmal.']);
        }
    } else {
        output(false, ['Sie sind nicht angemeldet!']);
    }
}

if (isset($_POST['myVideos'])) {
    $id = $_SESSION['id'];
    $res = $connect->query("SELECT id, title, poster FROM videos WHERE owner = '$id'");
    $data = [];
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }
    if (count($data) == 0) {
        output(false, ['Sie haben noch kein Video hochgeladen!']);
    }
    output(true, $data);
}

if (isset($_POST['likedVideos'])) {
    $tN = 'likedVideos';
    getVideos($tN, $tN, $connect, $id);
}


if (isset($_POST['history'])) {
    $tN = 'history';
    getVideos($tN, $tN, $connect, $id);
}

function getVideos($name, $tableN, $connect, $id)
{
    $res = $connect->query("select v.id, v.poster, v.title from videos v join $tableN l on(l.video = v.id) where l.id = '$id'");

    if ($res->num_rows > 0) {
        $data = [];
        while ($r = $res->fetch_assoc()) {
            $data[] = $r;
        }
        output(true, $data);
    }
    $msg = '';
    if ($name == 'history') {
        $msg = 'Sie haben noch kein Video angeschaut!';
    } else {
        $msg = 'Sie haben noch keine gelikte Videos!';
    }
    output(false, [$msg]);
}

if (isset($_POST['abos'])) {
    $res = $connect->query("select k.name, p.pname from konto k join profilandcoverpic p on(k.id = p.id) join channels c on (c.channel = k.id) where c.follower = $id");

    if ($res->num_rows > 0) {
        $data = [];
        while ($r = $res->fetch_assoc()) {
            $data[] = $r;
        }
        output(true, $data);
    }
    output(false, ['Sie haben noch niemanden abonniert!']);
}
