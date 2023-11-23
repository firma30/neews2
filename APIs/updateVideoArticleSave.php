<?php

require("../config/conn.php");

    $uId = $_POST['uId'];
    $videoArticleId = $_POST['video_article_id'];

    $checkVideoArticle = "SELECT * FROM `video_saves` WHERE `uId`= '$uId' AND `video_article_id` = '$videoArticleId'";
    $check = mysqli_query($conn,$checkVideoArticle);

    if($check)
    {
        if(mysqli_num_rows($check) > 0)
        {
            //Removing save
            $deleteSave = "DELETE FROM `video_saves` WHERE `uId`= '$uId' AND `video_article_id` = '$videoArticleId'";
            $delete = mysqli_query($conn,$deleteSave);

            if($delete)
            {
                //Decrementing Save 
                $updateVideoArticleSave = "UPDATE `video_articles` SET `saves` = `saves`-1 WHERE `id` = '$videoArticleId'";
                $update = mysqli_query($conn,$updateVideoArticleSave);

                if($update)
                {
                    $response['status_code'] = 0;
                    $response['message'] = 'Video article save updated successfully';
                    
                }
                else
                {
                    $response['status_code'] = 4;
                    $response['message'] = 'Some error occurred while updating video article saves';
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
            $createSave = "INSERT INTO `video_saves` (`id`, `uId`, `video_article_id`, `date`) VALUES (NULL, '$uId', '$videoArticleId', current_timestamp())";
            $create = mysqli_query($conn,$createSave);

            if($create)
            {
                //Incrementing saves
                $updateArticleSave = "UPDATE `video_articles` SET `saves` = `saves`+1 WHERE `id` = '$videoArticleId'";
                $update = mysqli_query($conn,$updateArticleSave);

                if($update)
                {
                    $response['status_code'] = 0;
                    $response['message'] = 'Video article save updated successfully';
                    
                }
                else
                {
                    $response['status_code'] = 4;
                    $response['message'] = 'Some error occurred while updating video article saves';
                }
            }
            else
            {
                $response['status_code'] = 4;
                $response['message'] = 'Some error occurred while creating video article save';
                
            }
        }
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while updating likes';
        
    }

    echo json_encode($response);

?>