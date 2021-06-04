<?php
include 'connection.php';

if (isset($_POST['searchData'])) {
    $searchData = $_POST['searchData'];
    $output = [];
    $sStr = strtolower($searchData['sStr']);

    if (count($searchData) == 1) {
        $found = $connect->query("SELECT k.name, p.pname FROM konto k join profilandcoverpic p using(id) WHERE LOWER(k.name) LIKE '%$sStr%'");

        if ($found->num_rows > 0) {
            while ($row = $found->fetch_assoc()) {
                $output['channels'][] = ['name' => $row['name'], 'pname' => $row['pname']];
            }
        }
    }

    $stm = "SELECT id, title, poster, catag, EXTRACT(YEAR FROM uploaddate) as year FROM videos HAVING LOWER(title) LIKE '%$sStr%'";

    if (isset($searchData['filter'])) {
        $searchData = $searchData['filter'];
        if (isset($searchData['categorie'])) {
            $catag = $searchData['categorie'];
            $stm .= " and json_contains(catag, '[" . '"' . $catag . '"' . "]')";
        }
        if (isset($searchData['year'])) {
            $year = $searchData['year'];
            $stm .= " and year = $year";
        }
        if (isset($searchData['order'])) {
            $stm .= ' order by ';
            switch ($searchData['order']) {
                case 'Neuesten zuerst':
                    $o = 'uploaddate desc';
                    break;
                case 'Ältesten zuerst':
                    $o = 'uploaddate';
                    break;
                case 'Berühmtesten zuerst':
                    $o = 'clicks, likes desc';
                    break;
                case 'Berühmtesten zuletzt':
                    $o = 'clicks, likes';
                    break;
                default:
                    break;
            }
            $stm .= $o;
        }
    }

    $found = $connect->query($stm);

    if ($found->num_rows > 0) {
        while ($row = $found->fetch_assoc()) {
            $output['videos'][] = ['id' => $row['id'], 'title' => $row['title'], 'poster' => $row['poster']];
        }
    }

    echo json_encode($output);
}

if (isset($_POST['catags'])) {
    function giveMeFCatags($connect, $start = 0, $limit = 9)
    {
        $res = $connect->query("SELECT catag FROM videos order by clicks, likes desc");
        $data = [];
        $more = false;
        $fData = $res->fetch_all();

        for ($i = $start; ($more = ($i < count($fData))) && $i <= $limit; $i++) {
            $d = json_decode($fData[$i][0]);
            $data = array_merge($d, $data);
        }

        return ['arr' => array_values(array_unique($data, SORT_REGULAR)), 'more' => $more];
    }

    function giveMeFRCatags($connect, $start = 0, $limit = 10)
    {
        $take = true;
        $data = [];
        $fData = null;
        $more = null;
        while ($take) {
            $catag = giveMeFCatags($connect, $start, $limit);
            $fData = $catag['arr'];
            $data = array_merge($data, $fData);
            $more = $catag['more'];
            $take = (count($data) < 10 && $more == true);
            $limit += 10;
            $start += 10;
        }

        $r_index_arr = [];
        for ($i = 0; $i < count($data); $i++) {
            $r = -1;
            while ($r == -1 || in_array($r, $r_index_arr)) {
                $r = random_int(0, (count($data) - 1));
            }
            $r_index_arr[] = $r;
        }

        $newData = [];
        foreach ($r_index_arr as $ind) {
            $newData[] = $data[$ind];
        }
        return $newData;
    }



    echo json_encode(giveMeFRCatags($connect));
}


$connect->close();
