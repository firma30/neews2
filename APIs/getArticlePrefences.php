<?php

require("../config/conn.php");

$uId = $_POST['uId'];

$fetchArticlePrefences = "SELECT * FROM `categories` WHERE `id` NOT IN ( SELECT category_id FROM user_intrest WHERE `uId` = '$uId')";
$fetch = mysqli_query($conn, $fetchArticlePrefences);

if ($fetch) {
    if (mysqli_num_rows($fetch) > 0) {
        while ($row = $fetch->fetch_assoc()) {
            $response['category_list'][] = $row;
        }
    } else {
        $response['status_code'] = 1;
        $response['message'] = 'No categories available';
    }
} else {
    $response['status_code'] = 4;
    $response['message'] = 'Some error occurred while fetching categories';
}


echo json_encode($response);
