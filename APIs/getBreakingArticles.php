<?php

require("../config/conn.php");

    $language = $_POST['language'];

    $fetchBreakingArticles = "SELECT * FROM `articles` WHERE `is_breaking` = 1 AND `language` = '$language' ORDER BY `date_created` DESC LIMIT 10";
    $getBreakingArticles = mysqli_query($conn,$fetchBreakingArticles);

    if($getBreakingArticles)
    {
        if(mysqli_num_rows($getBreakingArticles) > 0)
        {
            $response['status_code'] = 0;
            $response['message'] = 'Breaking articles fetched successfully';

            while($row = $getBreakingArticles->fetch_assoc())
            {
                $response['breaking_articles'][] = $row;
            }
            
        }
        else
        {
            $response['status_code'] = 1;
            $response['message'] = 'No breaking articles available';
        }
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while fetching breaking articles';
        
    }

    echo json_encode ($response);

    


?>