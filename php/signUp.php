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

if (isset($_POST['signUpData'])) {
    $signUpData = json_decode($_POST['signUpData']);
    $signUpData->name = $connect->real_escape_string($signUpData->name);
    $signUpData->pass1 = $connect->real_escape_string($signUpData->pass1);
    $signUpData->pass2 = $connect->real_escape_string($signUpData->pass2);
    $signUpData->email = $connect->real_escape_string($signUpData->email);

    $now = new DateTime();
    $bDay = new DateTime($signUpData->bDay);
    $diff = $now->getTimestamp() - $bDay->getTimestamp();
    $ePat = "/^[A-zäöüßÄÖÜ\.\_\-\d]{2,40}@([A-z\-\_]+\.){1,5}[A-z]{2,10}$/i";
    $output = [];

    if (strlen($signUpData->name) < 3 || strlen($signUpData->name) > 25) {
        $Output[] = 'Die Länge des Namens muss zw. 3 - 25 sein!';
    }

    if ($diff < 0) {
        $output[] = 'Ihr Geburtsdatum stimmt nicht!';
    }

    if (strlen($signUpData->country) < 3 || strlen($signUpData->country) > 30) {
        $output[] = 'Das Land muss eine Länge zw. 3 - 30 haben!';
    }

    if (!preg_match($ePat, $signUpData->email)) {
        $output[] = 'E-Mail muss in richtiger Form haben!';
    }

    if (strlen($signUpData->pass1) < 4) {
        $output[] = 'Passwort muss mind. 4 Zeichen haben!';
    } else if ($signUpData->pass1 != $signUpData->pass2) {
        $output[] = 'Passwörter stimmen nicht überein!';
    }

    if (count($output) == 0) {
        $sql = "select name from konto where name = '$signUpData->name'";
        $db_data =
            $data = new stdClass();
        if ($connect->query($sql)->num_rows <= 0) {
            $output = creatAccount($signUpData, $connect);
            output($output['success'], $output['msg']);
        } else {
            $output[] = $signUpData->name . ' existiert schon!';
            output(false, $output);
        }
    } else {
        output(false, $output);
    }
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
        cProfileImg($fileName = $sData->name . 'ProfImg.png');
        $connect->query("INSERT INTO profilandcoverpic (id, pname, cname) VALUE('$key', '$fileName', 'standart.jpg')");
        session_start();
        $_SESSION['id'] = $key;
        return ['success' => true, 'msg' => ''];
    }
    return ['success' => false, 'msg' => 'Sie konnten nicht registriest werden! Irgendetwas stimmte nicht.'];
}

function cProfileImg($fileName)
{
    $text = explode(' ', $fileName);
    if (count($text) > 1) {
        $text = substr($text[0], 0, 1) . substr($text[1], 0, 1);
    } else {
        $text = substr($text[0], 0, 2);
    }

    $colors = [
        [[33, 33, 33], [233, 233, 23]],
        [[240, 243, 50], [247, 114, 37]],
        [[54, 192, 107], [111, 111, 111]]
    ];
    $cIndex = random_int(- (count($colors) - 1), (count($colors) - 1));
    $bgIndex = $cIndex >= 0 ? 0 : 1;
    $tIndex = $cIndex >= 0 ? 1 : 0;
    $cIndex = ($cIndex >= 0 ? $cIndex : $cIndex * -1);
    $font = realpath('C:\Windows\Fonts\impact.ttf');
    $fontsize = 80;
    $coords = imageftbbox($fontsize, 0, $font, $text);
    $x = (300 - $coords[4]) / 2;
    $y = (300 - $coords[5]) / 2;
    $img = imagecreatetruecolor(300, 300);
    $bg =  imagecolorallocate($img, $colors[$cIndex][$tIndex][0], $colors[$cIndex][$bgIndex][1], $colors[$bgIndex][$bgIndex][2]);
    $tColor = imagecolorallocate($img, $colors[$cIndex][$tIndex][0], $colors[$cIndex][$tIndex][1], $colors[$cIndex][$tIndex][2]);

    imagefilledrectangle($img, 0, 0, 300, 300, $bg);
    imagettftext($img, 80, 0, $x, $y, $tColor, $font, $text);

    imagepng($img, '../img/profileImg/' . $fileName);
    imagedestroy($img);
}

$connect->close();
