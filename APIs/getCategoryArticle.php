<?php

require("../config/conn.php");

    $categoryId = $_POST['category_id'];
    $limit = $_POST['limit'];

    if($limit == 0)
    {
        $fetchArticles = "SELECT * FROM `articles` WHERE `category_id` = '$categoryId' ORDER BY `date_created` DESC";
    }
    else
    {
        $fetchArticles = "SELECT * FROM `articles` WHERE `category_id` = '$categoryId' ORDER BY `date_created` DESC LIMIT $limit";
    }


    
    $getArticles = mysqli_query($conn,$fetchArticles);
    

    if($getArticles)
    {
        if(mysqli_num_rows($getArticles) > 0 )
        {
            $response['status_code'] = 0;
            $response['message'] = 'Articles fetched';
            
            while($row = $getArticles->fetch_assoc()){
                $response['articles'][] = $row;
            }
            
        }
        else
        {
            $response['status_code'] = 1;
            $response['message'] = 'No articles available now';
            
        }
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Internal error occurred while fetching articles';
        
    }

    echo json_encode($response);
