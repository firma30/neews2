<?php

require("../config/conn.php");

    $uId = $_POST['uId'];

    $fetchUserSavedVideoArticles = "SELECT * FROM `video_articles` INNER JOIN `video_saves` ON video_articles.id = video_saves.video_article_id
    WHERE video_saves.uId = '$uId';";
    $getSavedVideoArticles = mysqli_query($conn,$fetchUserSavedVideoArticles);

    if($getSavedVideoArticles)
    {
        if(mysqli_num_rows($getSavedVideoArticles) > 0)
        {
            $response['status_code'] = 0;
            $response['message'] = 'Articles fetched successfully';
            
            while($row = $getSavedVideoArticles->fetch_assoc())
            {
                $response['video_articles'][] = $row;
            }

        }
        else
        {
            $response['status_code'] = 1;
            $response['message'] = 'No articles found';
            
        }
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while fetching user saved articles';
        
    }


    echo json_encode($response);

?>