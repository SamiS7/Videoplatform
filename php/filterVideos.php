<?php
include 'connection.php';

function getSelections()
{
    return 'select v.id, title, v.poster';
}

function mostSeenSTM()
{
    return getSelections() .  ", d.* from videos v join dailydata d on(v.id = d.video) order by d.likes desc";
}

function mostLikedSTM()
{
    return getSelections() .  ", d.* from videos v join dailydata d on(v.id = d.video) order by d.likes, v.likes desc";
}

function recommended($id, $connect)
{
    $data = [];

    if ($res = $connect->query(getSelections() . " from videos v where owner in (select channel from channels where follower = '$id')")) {
        while ($r = $res->fetch_assoc()) {
            $data[] = $r;
        }
    }

    $i = count($data);
    if ($i < 10) {
        $res = $connect->query(getSelections() . " from videos v");

        while (($r = $res->fetch_assoc()) && $i < 10) {
            if (!in_array($r, $data)) {
                $data[] = $r;
            }
            $i++;
        }
    }

    $r_index_arr = [];
    for ($i = 0; $i < count($data) && $i < 10; $i++) {
        $r = -1;
        while ($r == -1 || in_array($r, $r_index_arr)) {
            $r = random_int(0, (count($data) - 1));
        }
        $r_index_arr[] = $r;
    }

    $newData = [];
    foreach ($r_index_arr as $i) {
        $newData[] = $data[$i];
    }
    return ['success' => true, 'data' => $newData];
}

function mostRecentSTM()
{
    return getSelections() .  " from videos v order by uploaddate desc";
}

function catagSTM($catag)
{
    return getSelections() .  " from videos v where JSON_CONTAINS(v.catag, '$catag') order by v.clicks";
}

function tagSTM($tag)
{
    return getSelections() .  " from videos v where JSON_CONTAINS(v.tags, '$tag') order by v.clicks";
}

function ownerSTM($name)
{
    return getSelections() .  " from videos v where owner = (select id from konto where name = '$name')";
}

function giveMe($stm)
{
    $data = [];
    $connect = $GLOBALS['connect'];

    if ($res = $connect->query($stm)) {
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        return ['success' => true, 'data' => $data];
    }
    return ['success' => false, 'data' => null];
}

function checkLimit($data, $limit = 20, $start = 0)

{
    if (count($data) > $start) {
        $newData = [];
        for ($i = $start; $i < $limit && $i < count($data['data']); $i++) {
            $newData[] = $data['data'][$i];
        }
        return ['success' => $data['success'], 'data' => $newData, 'more' => count($newData) > count($data['data'])];
    }
    $data['more'] = false;
    return $data;
}

if (isset($_POST['mostSeen'])) {
    echo json_encode(checkLimit(giveMe(mostSeenSTM())));
}

if (isset($_POST['mostLiked'])) {
    echo json_encode(checkLimit(giveMe(mostLikedSTM())));
}

if (isset($_POST['mostRecent'])) {
    echo json_encode(checkLimit(giveMe(mostRecentSTM()), 19));
}

if (isset($_POST['catag'])) {
    $c = $GLOBALS['connect']->real_escape_string(($_POST['catag']));
    echo json_encode(giveMe(catagSTM($c)));
}

if (isset($_POST['tag'])) {
    $t = $GLOBALS['connect']->real_escape_string(($_POST['tag']));
    echo json_encode(giveMe(tagSTM($t)));
}

if (isset($_POST['fromChannel'])) {
    $c = $GLOBALS['connect']->real_escape_string(($_POST['fromChannel']));
    echo json_encode(giveMe(ownerSTM($c)));
}

if (isset($_POST['recommended'])) {
    session_start();
    $id = null;
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
    }
    echo json_encode(recommended($id, $connect));
}

include 'checkDailyData.php';
