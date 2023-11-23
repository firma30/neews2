<?php

require("../config/conn.php");

    $uId = $_POST['uId'];
    $articleId = $_POST['article_id'];

    //Chech View
    $checkViews = "SELECT * FROM `views` WHERE `uId` = '$uId' AND `article_id` = '$articleId'";
    $check = mysqli_query($conn,$checkViews);

    if($check)
    {
        if(mysqli_num_rows($check) > 0)
        {
            $response['status_code'] = 1;
            $response['message'] = 'Already viewed';
            
        }
        else
        {
            //Update Article VIews
            $updateArticleViews = "UPDATE `articles` SET `views` = `views`+1 WHERE `id` = '$articleId'";
            $update = mysqli_query($conn,$updateArticleViews);

            if($update)
            {
                $createView = "INSERT INTO `views` (`id`, `uId`, `article_id`, `date_created`) VALUES (NULL, '$uId', '1', current_timestamp());";
                $create = mysqli_query($conn,$createView);

                if($create)
                {
                    $response['status_code'] = 0;
                    $response['message'] = 'View created successfully';
                }
                else
                {
                    $response['status_code'] = 4;
                    $response['message'] = 'Some error ocurred while creating view';
                    
                }
            
            }
            else
            {
                $response['status_code'] = 4;
                $response['message'] = 'Some error occurred while updating views';
                
            }
        }
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occured while checking vieews';
        
    }

    echo json_encode($response);


?>