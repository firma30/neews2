<?php
require("config/conn.php");


function getNoData($name, $filter, $filtervalue)
{
    global $conn;
    if ($filter == "") {
        $sql = "SELECT * FROM `$name`";
    } else {
        $sql = "SELECT * FROM `$name` WHERE `$filter`='$filtervalue'";
    }
    $query_run = mysqli_query($conn, $sql);
    if ($query_run) {
        $data = mysqli_num_rows($query_run);
    } else {
        $data = "Error Occured";
    }
    return $data;
}

function filter($data)
{
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

function getDailyQuizSetting($data)
{
    global $conn;
    $sql = "SELECT * FROM `dailyquizsettings` WHERE `name`='$data' LIMIT 1";
    $query_run  = mysqli_query($conn, $sql);

    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            foreach ($query_run as $row) {
                $data = $row['value'];
            }
        } else {
            $data = "Make Setting First!";
        }
    } else {
        $data = "ERROR!";
    }

    return $data;
}

function getSettingValue($table, $data)
{
    global $conn;
    $sql = "SELECT * FROM `$table` WHERE `username`='$data' LIMIT 1";
    $query_run  = mysqli_query($conn, $sql);

    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            foreach ($query_run as $row) {
                $data = $row['username'];
            }
        } else {
            $data = "Some Error";
        }
    } else {
        $data = "ERROR!";
    }

    return $data;
}

function getSettingADValue($table, $data)
{
    global $conn;
    $sql = "SELECT * FROM `$table` WHERE `name`='$data' LIMIT 1";
    $query_run  = mysqli_query($conn, $sql);

    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            foreach ($query_run as $row) {
                $data = $row['adId'];
            }
        } else {
            $data = "Some Error";
        }
    } else {
        $data = "ERROR!";
    }

    return $data;
}

function getAdminLoginDetails($table, $value)
{
    global $conn;
    $sql = "SELECT * FROM `$table` LIMIT 1";
    $query_run  = mysqli_query($conn, $sql);

    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            foreach ($query_run as $row) {
                $data = $row["$value"];
            }
        } else {
            $data = "Some Error";
        }
    } else {
        $data = "ERROR!";
    }

    return $data;
}

function getAppInfo($table, $data)
{
    global $conn;
    $sql = "SELECT * FROM `$table` WHERE `name`='$data' LIMIT 1";
    $query_run  = mysqli_query($conn, $sql);

    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            foreach ($query_run as $row) {
                $data = $row['description'];
            }
        } else {
            $data = "Some Error";
        }
    } else {
        $data = "ERROR!";
    }

    return $data;
}

// function sendFCMNotification($title, $description, $imageUrl, $serverKey, $token)
// {
//     $url = "https://fcm.googleapis.com/fcm/send";
//     $token = "cntFe94dTZSvJHj9Ccs2iL:APA91bEQQ5K5kcl5zYHGBecq5_IljLjbRcVL8ITtvGQZj6ylhQlhy1X3_qkwS9Eov82aXUWV97JOS85wkY1OPbWDSOMtrwKl-dWc-nR8B4IUjsqteVT9ogYxRG0Q5nonsUQF_bc6CT6e";
//     $serverKey = 'AAAAziVUUnY:APA91bFdPBNV-77-HSFo7D1fCNaEZ8Q7slzsby-vZUTMlomgIt8nJ5zbjuDokBpPwhE7iJqOcoupsM8l7fkUbsfMLnxVm-34lzQH8WZmNosPPPMLv0aQ3pyObgqbdLpQZZzpFXSmL3w_';
//     $title = "Title";
//     $description = "message";
//     $notification = array('title' => $title, 'message' => $description, 'image' => $imageUrl,  'sound' => 'default', 'badge' => '1');
//     $arrayToSend = array('to' => $token, 'notification' => $notification, 'priority' => 'high');
//     $json = json_encode($arrayToSend);
//     $headers = array();
//     $headers[] = 'Content-Type: application/json';
//     $headers[] = 'Authorization: key=' . $serverKey;
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     //Send the request
//     $response = curl_exec($ch);
//     //Close request
//     if ($response === FALSE) {
//         return 'Gagal mengirim notifikasi: ' . curl_error($ch);
//     }

//     curl_close($ch);

//     return 'Notifikasi berhasil dikirim';
// }


function sendFCMNotification($title, $message, $articleId)
{
    $url = "https://fcm.googleapis.com/fcm/send";
    $token = "e4GD3A5ESj-Y7Aq-1Me1Ae:APA91bGkWZJKWqHy1y-NW10SrAoNVaTeEyGLOxA5e494t6ErDibCwo4z42cmEwr8m9GdDQU3Bwi_YBfB3b-CVX3ul8MmDvOAOMfTyw8xwtWZL81ONyCxOZSn_cZa0MlgYd01_kCok3uN";
    $serverKey = 'AAAAziVUUnY:APA91bFdPBNV-77-HSFo7D1fCNaEZ8Q7slzsby-vZUTMlomgIt8nJ5zbjuDokBpPwhE7iJqOcoupsM8l7fkUbsfMLnxVm-34lzQH8WZmNosPPPMLv0aQ3pyObgqbdLpQZZzpFXSmL3w_';
    $imageUrl = "";


    $notification = [
        'title' => $title,
        'body' => $message,
        'image' => $imageUrl,
    ];

    $data = [
        'title' => $title,
        'message' => $message,
        'article_Id' => $articleId,
    ];

    $arrayToSend = array(
        'to' => $token,
        'notification' => $notification,
        'data' => $data,
    );
    $json = json_encode($arrayToSend);
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Authorization: key=' . $serverKey;

    $headers = [
        'Content-Type: application/json',
        'Authorization: key=' . $serverKey,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Kirim permintaan
    $response = curl_exec($ch);

    if ($response === false) {
        return 'Gagal mengirim notifikasi: ' . curl_error($ch);
    }

    curl_close($ch);

    // mengembalikan respons FCM 
    return $response;
}
