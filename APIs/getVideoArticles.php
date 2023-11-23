<?php

require("../config/conn.php");

$fetchVideoArticles = "SELECT * FROM `video_articles` ORDER BY RAND(),`date_created` DESC ";
$getVideoArticles = mysqli_query($conn, $fetchVideoArticles);

if ($getVideoArticles) {
    if (mysqli_num_rows($getVideoArticles) > 0) {
        $response['status_code'] = 0;
        $response['message'] = 'Video articles fetched successfully';

        while ($row = $getVideoArticles->fetch_assoc()) {
            $response['video_articles'][] = $row;
        }
    } else {
        $response['status_code'] = 1;
        $response['message'] = 'No video articles available at this moment';
    }
} else {
    $response['status_code'] = 4;
    $response['message'] = 'Some error occurred while fetching video articles';
}


echo json_encode($response);
