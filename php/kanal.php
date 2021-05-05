<?php
$db_host = 'localhost';
$db_datenbank = 'video-projekt';
$db_username = 'video-projekt';
$db_password = 'passw';
session_start();
$id = null;

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
}

$connect = new mysqli($db_host, $db_username, $db_password, $db_datenbank);

if ($connect->connect_error) {
    die("Datenbank connection failed" . $connect->connect_error);
}

if (isset($_POST['aboCounter'])) {
    $channel = $connect->real_escape_string($_POST['aboCounter']);
    $res = $connect->query("SELECT abos FROM konto WHERE id = '$channel'");
    if ($res->num_rows > 0) {
        $res = $res->fetch_assoc()['abos'];
    } else {
        $res = '';
    }
    echo $res;
}

if (isset($_POST['checkKonto'])) {
    if ($id != null) {
        $res = $connect->query("SELECT name, abos FROM konto WHERE id = '$id'");

        if ($res->num_rows > 0) {
            $res = $res->fetch_assoc();
            echo json_encode(['abos' => $res['abos'], 'name' => $res['name'],  'logedIn' => true]);
        } else {
            echo json_encode(['abos' => 'Existiert nicht!', 'name' => $id,  'logedIn' => false]);
        }
    } else {
        echo json_encode(['abos' => 'Nicht angemeldet!', 'name' => '', 'logedIn' => false]);
    }
}

if (isset($_POST['changedName'])) {
    $alert;

    if ($id != null) {
        $chNData = $_POST['changedName'];
        $name = $connect->real_escape_string($chNData['name']);
        $res = $connect->query("SELECT name FROM konto WHERE name = '$name'");
        if ($res->num_rows > 0) {
            $alert['success'] = false;
            $alert['msg'] = "$name gibt es Schon!";
        }

        if (!isset($alert)) {
            if ($connect->query("UPDATE konto SET name = '$name' WHERE id = '$id'")) {
                $alert['success'] = true;
                $alert['msg'] = "Ihr neuer Name ist $name.";
            }
        }
    } else {
        $alert = ['success' => false, 'msg' => 'Nicht angemeldet!'];
    }
    echo json_encode($alert);
}

if (isset($_POST['followAndAboCounter'])) {
    $cData = json_decode($_POST['followAndAboCounter']);
    $cData->channel = $connect->real_escape_string($cData->channel);

    $output = abocheck($id, $cData->channel, $connect);
    echo json_encode($output);
}

if (isset($_POST['following'])) {
    if ($id != null) {
        $x = json_decode($_POST['following']);
        $x->channel = $connect->real_escape_string($x->channel);

        if (abocheck($id, $x->channel, $connect)->following) {
            $connect->query("DELETE FROM channels where follower = '$id' AND channel = '(select id from konto where id = '$x->channel')'");
            $connect->query("UPDATE konto SET abos = abos - 1 WHERE id = '$x->channel'");
        } else if (exists($x->channel, $connect)) {
            $connect->query("INSERT INTO channels VALUE ('$id', '(select id from konto where id = '$x->channel')')");
            $connect->query("UPDATE konto SET abos = abos + 1 WHERE id = '$x->channel'");
        }
    }
}

function abocheck($id, $channel, $connect)
{
    $data = new stdClass();
    if ($id != null) {
        $following = $connect->query("SELECT * FROM channels WHERE follower = '$id' AND channel = '(select id from konto where id = '$channel')'");
        $data->following = $following->num_rows > 0;
        $data->logedIn = true;
    } else {
        $data->logedIn = false;
    }
    $abosC = $connect->query("SELECT abos FROM konto WHERE id = '$channel'");
    if ($abosC->num_rows > 0) {
        $abosC = $abosC->fetch_assoc()['abos'];
    } else {
        $abosC = 'Undefind';
    }
    $data->abos = $abosC;

    return $data;
}

function exists($ch, $con)
{
    return $con->query("SELECT * FROM konto WHERE id = '$ch'")->num_rows > 0;
}

$connect->close();
