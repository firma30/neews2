<?php

require("../config/conn.php");

$language = $_POST['language'];

$fetchAllCategories = "SELECT * FROM `categories` WHERE `language` = '$language' ORDER BY `id` ASC";
$getCategories = mysqli_query($conn, $fetchAllCategories);


if ($getCategories) {
    if (mysqli_num_rows($getCategories) > 0) {
        $response['status_code'] = 0;
        $response['message'] = 'Categories fetched successfully';

        while ($row = $getCategories->fetch_assoc()) {
            $response['categories'][] = $row;
        }
    } else {
        $response['status_code'] = 1;
        $response['message'] = 'No categories available';
    }
} else {
    $response['status_code'] = 4;
    $response['message'] = 'Some error occurred while fetching categories' . mysqli_error($conn);;
}

echo json_encode($response);
