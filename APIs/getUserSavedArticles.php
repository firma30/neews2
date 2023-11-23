<?php

require("../config/conn.php");

    $uId = $_POST['uId'];

    $fetchUserSavedArticles = "SELECT * FROM `articles` INNER JOIN `saves` ON articles.id = saves.article_id
    WHERE saves.uId = '$uId';";
    $getSavedArticles = mysqli_query($conn,$fetchUserSavedArticles);

    if($getSavedArticles)
    {
        if(mysqli_num_rows($getSavedArticles) > 0)
        {
            $response['status_code'] = 0;
            $response['message'] = 'Articles fetched successfully';
            
            while($row = $getSavedArticles->fetch_assoc())
            {
                $response['articles'][] = $row;
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