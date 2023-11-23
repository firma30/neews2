<?php

require("../config/conn.php");

    $uId = $_POST['uId'];
    $articleId = $_POST['article_id'];

    $checkArticle = "SELECT * FROM `saves` WHERE `uId`= '$uId' AND `article_id` = '$articleId'";
    $check = mysqli_query($conn,$checkArticle);

    if($checkArticle)
    {
        if(mysqli_num_rows($check) > 0)
        {
            //Removing save
            $deleteSave = "DELETE FROM `saves` WHERE `uId`= '$uId' AND `article_id` = '$articleId'";
            $delete = mysqli_query($conn,$deleteSave);

            if($delete)
            {
                //Decrementing Save 
                $updateArticleSave = "UPDATE `articles` SET `saves` = `saves`-1 WHERE `id` = '$articleId'";
                $update = mysqli_query($conn,$updateArticleSave);

                if($update)
                {
                    $response['status_code'] = 0;
                    $response['message'] = 'Article save updated successfully';
                    
                }
                else
                {
                    $response['status_code'] = 4;
                    $response['message'] = 'Some error occurred while updating article saves';
                }

            }
            else
            {
                $response['status_code'] = 4;
                $response['message'] = 'Some error occurred while deleting save';
                
            }
        }
        else
        {
            //Adding save
            $createSave = "INSERT INTO `saves` (`id`, `uId`, `article_id`, `date`) VALUES (NULL, '$uId', '$articleId', current_timestamp())";
            $create = mysqli_query($conn,$createSave);

            if($create)
            {
                //Incrementing saves
                $updateArticleSave = "UPDATE `articles` SET `saves` = `saves`+1 WHERE `id` = '$articleId'";
                $update = mysqli_query($conn,$updateArticleSave);

                if($update)
                {
                    $response['status_code'] = 0;
                    $response['message'] = 'Article save updated successfully';
                    
                }
                else
                {
                    $response['status_code'] = 4;
                    $response['message'] = 'Some error occurred while updating article saves';
                }
            }
            else
            {
                $response['status_code'] = 4;
                $response['message'] = 'Some error occurred while creating article save';
                
            }
        }
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while checking article save';
        
    }


    echo json_encode($response);


?>