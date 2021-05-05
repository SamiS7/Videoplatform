<?php
function getConnect()
{
    $db_host = 'localhost';
    $db_datenbank = 'video-projekt';
    $db_username = 'video-projekt';
    $db_password = 'passw';

    return new mysqli($db_host, $db_username, $db_password, $db_datenbank);
}

if (isset($_POST['checkProfileImg'])) {
    $name = $_POST['checkProfileImg'];
    session_start();
    if (strlen($name) == 0 && !isset($_SESSION['id'])) {
        echo 'standart.jpg';
        exit;
    }
    $connect = getConnect();

    if ($connect->connect_error) {
        die("Datenbank connection failed" . $connect->connect_error);
    }
    if (strlen($name) == 0) {
        $id = $_SESSION['id'];
    } else {
        if ($res = $connect->query("SELECT id FROM konto WHERE name = '$name'")) {
            if ($res->num_rows > 0) {
                $id = $res->fetch_assoc()['id'];
            } else {
                echo 'standart.jpg';
                exit();    
            }
        } else {
            echo 'standart.jpg';
            exit();
        }
    }
    if ($res = $connect->query("SELECT pName FROM profilandcoverpic WHERE id =$id")) {
        $res = $res->fetch_assoc();
        echo $res['pName'];
        $connect->close();
        exit;
    } else {
        echo 'standart.jpg';
    }
}

if (isset($_POST['checkCoverImg'])) {
    $name = $_POST['checkCoverImg'];
    session_start();
    if (strlen($name) == 0 && !isset($_SESSION['id'])) {
        echo 'standart.jpg';
        exit;
    }
    $connect = getConnect();

    if ($connect->connect_error) {
        die("Datenbank connection failed" . $connect->connect_error);
    }
    if (strlen($name) == 0) {
        $id = $_SESSION['id'];
    } else {
        if ($res = $connect->query("SELECT id FROM konto WHERE name = '$name'")) {
            if ($res->num_rows > 0) {
                $id = $res->fetch_assoc()['id'];
            } else {
                echo 'standart.jpg';
                exit();    
            }
        } else {
            echo 'standart.jpg';
            exit();
        }
    }
    if ($res = $connect->query("SELECT cName FROM profilandcoverpic WHERE id =$id")) {
        $res = $res->fetch_assoc();
        echo $res['cName'];
        $connect->close();
        exit;
    } else {
        echo 'standart.jpg';
    }
}
