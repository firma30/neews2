<?php

require("../config/conn.php");

    $uId = $_POST['uId'];
    $language = $_POST['language'];

    $fetchUSerIntrestArticles = "SELECT * FROM articles WHERE category_id IN (SELECT category_id FROM user_intrest WHERE uId = '$uId') AND `language` = '$language' ORDER BY `date_created` DESC;";
    $getUserIntrestArticles = mysqli_query($conn,$fetchUSerIntrestArticles);

    if($getUserIntrestArticles)
    {
        if(mysqli_num_rows($getUserIntrestArticles) > 0)
        {
            $response['status_code'] = 0;
            $response['message'] = 'User intrest articles fetched successfully';
            
            while($row = $getUserIntrestArticles->fetch_assoc())
            {
                $response['user_intrest_articles'][] = $row;
            }
            
        }
        else
        {
            $response['status_code'] = 1;
            $response['message'] = 'No artilces available';
            
        }
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while fetching user intrest articles';
        
    }

    echo json_encode($response);

?>