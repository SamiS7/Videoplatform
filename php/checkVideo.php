<?php
include 'connection.php';

if (isset($_POST['uploadVideo'])) {
    $videoID = $_POST['uploadVideo'];
    if ($res = $connect->query("select v.name as vName, title, owner, v.poster, p.pname, k.name as kName
    from videos v join profilandcoverpic p on(v.owner = p.id) join konto k on(v.owner = k.id) where v.id = $videoID")) {
        $row = $res->fetch_assoc();

        $owner = $row['owner'];
        unset($row['owner']);
        $res = $connect->query("SELECT id, poster, title FROM videos WHERE owner = $owner and id != $videoID");
        $moreVideos = [];
        $i = 0;

        while (($r = $res->fetch_assoc()) && $i < 10) {
            $moreVideos[] = $r;
            $i++;
        }

        $famVideos = [];
        if (count($moreVideos) < 3) {
            $res = $connect->query("SELECT id, poster, title FROM videos where id != $videoID order by clicks");
            $i = 0;

            while (($r = $res->fetch_assoc()) && $i < (10 - count($moreVideos))) {
                if (!in_array($r, $moreVideos)) {
                    $famVideos[] = $r;
                }
                $i++;
            }
        }

        echo json_encode(['exists' => true, 'data' => $row, 'more' => ['moreVideos' => $moreVideos, 'famVideos' => $famVideos]]);
    } else {
        echo json_encode(['exists' => false]);
    }
}

include 'checkDailyData.php';

session_start();
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
} else {
    exit;
}

function checkLike($id, $video, $connect)
{
    $res = $connect->query("SELECT id FROM videos WHERE id = $video");
    if ($res->num_rows > 0) {
        $res = $connect->query("SELECT id FROM likedvideos WHERE video = $video AND id = '$id'");
        if ($res->num_rows > 0) {
            return ['exists' => true, 'likes' => true];
        }
        return ['exists' => true, 'likes' => false];
    }
    return ['exists' => false];
}

function checkHistory($id, $video, $connect)
{
    if ($connect->query("SELECT id FROM history WHERE id = '$id' AND video = $video")->num_rows > 0) {
        return true;
    }
    return false;
}

if (isset($_POST['like'])) {
    $video = $_POST['like'];
    $likes = checkLike($id, $video, $connect);

    if ($likes['exists'] && $likes['likes']) {
        $connect->query("UPDATE videos SET likes = likes - 1 WHERE id = $video");
        $connect->query("DELETE FROM likedvideos WHERE id = '$id' and video = $video");
        $connect->query("UPDATE dailydata SET likes = likes - 1 WHERE video = $video");
        echo json_encode(['likes' => false]);
    } else if ($likes['exists'] && !$likes['likes']) {
        $connect->query("UPDATE videos SET likes = likes + 1 WHERE id = $video");
        $connect->query("INSERT INTO likedvideos (id, video) values('$id', $video)");
        $connect->query("UPDATE dailydata SET likes = likes + 1 WHERE video = $video");
        echo json_encode(['likes' => true]);
    } else {
        echo json_encode(['likes' => false]);
    }
}

if (isset($_POST['checkHistory'])) {
    $video = $_POST['checkHistory'];

    if (!checkHistory($id, $video, $connect)) {
        $connect->query("UPDATE videos SET clicks = clicks + 1 WHERE id = $video");
        $connect->query("UPDATE dailydata SET clicks = clicks + 1 WHERE video = $video");
        $connect->query("INSERT INTO history (id, video) values('$id', $video)");
    }
}

if (isset($_POST['checkLike'])) {
    $video = $_POST['checkLike'];
    $likes = checkLike($id, $video, $connect);

    echo json_encode(['likes' => ($likes['exists'] && $likes['likes'])]);
}

$connect->close();
