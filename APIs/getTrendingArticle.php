<?php

require("../config/conn.php");

    $language = $_POST['language'];

    $fetchTrendingArticles = "SELECT * FROM `articles` WHERE `is_trending`= 1 AND `language` = '$language' ORDER BY `date_created` DESC LIMIT 40";
    $getTrendingArticles = mysqli_query($conn,$fetchTrendingArticles);

    if($getTrendingArticles)
    {
        if(mysqli_num_rows($getTrendingArticles) > 0)
        {
            $response['status_code'] = 0;
            $response['message'] = 'Trending articles fetched successfully';
            
            while($row = $getTrendingArticles->fetch_assoc())
            {
                $response['trending_articles'][] = $row;
            }
            
        }
        else
        {
            $response['status_code'] = 1;
            $response['message'] = 'No trending articles available';
            
        }
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Error occurred while fetching trending articles';
        
    }

    echo json_encode($response);


?>