<?php

require("../config/conn.php");

    $keyword = $_POST['keyword'];

    //Fetching Video Articles
    $fetchSearchVideoArticles = "SELECT * FROM `video_articles` WHERE `title` LIKE '%$keyword%' OR `category_name` LIKE '%$keyword%' ORDER BY `date_created` DESC";
    $getVideoArticles = mysqli_query($conn,$fetchSearchVideoArticles);

    if($getVideoArticles)
    {

        if(mysqli_num_rows($getVideoArticles) > 0)
        {
            $response['status_code'] = 0;
            $response['message'] = 'Video articles searched successfully';
            
            while($row = $getVideoArticles->fetch_assoc())
            {
                $response['video_articles'][] = $row;
            }
        }
        else
        {
            $response['status_code'] = 1;
            $response['message'] = 'No video articles found';
            
        }

        
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while searching articles';
        
    }


    echo json_encode($response);


?>