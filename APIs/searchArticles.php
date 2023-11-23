<?php

require("../config/conn.php");

    $keyword = $_POST['keyword'];
    $language = $_POST['language'];



    //Fetching Artocles
    if(trim($keyword) != '')
    {
        $fetchSearchArticles = "SELECT * FROM `articles` WHERE `title` LIKE '%$keyword%' OR `category_name` LIKE '%$keyword%' AND `language` = '$language' ORDER BY `date_created` DESC";
    }
    else
    {
        $fetchSearchArticles = "SELECT * FROM `articles` WHERE `language` = '$language' ORDER BY `date_created` DESC";
    }

    $getArticles = mysqli_query($conn,$fetchSearchArticles);

    if($getArticles)
    {
        if(mysqli_num_rows($getArticles) > 0)
        {
            $response['status_code'] = 0;
            $response['message'] = 'Articles searched successfully';

            while($row = $getArticles->fetch_assoc())
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
        $response['message'] = 'Some error occurred while searching articles';
        
    }


    echo json_encode($response);


?>