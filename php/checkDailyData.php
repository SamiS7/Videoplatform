<?php
include 'connection.php';

if ($res = $connect->query("select TIMESTAMPDIFF(hour, lastdate, now()) as diff from dailydatadate")) {
    if ($res->fetch_assoc()['diff'] >= 24) {
        $connect->query("update dailydata set likes = 0, clicks = 0");
        $connect->query("update dailydatadate set lastdate = now()");
    }
}
