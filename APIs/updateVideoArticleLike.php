<?php

require("../config/conn.php");

    $uId = $_POST['uId'];
    $videoArticleId = $_POST['video_article_id'];

    //Checkling likes
    $checkArticle = "SELECT * FROM `video_likes` WHERE `uId`= '$uId' AND `video_article_id` = '$videoArticleId'";
    $check = mysqli_query($conn,$checkArticle);

    if($check)
    {

        if(mysqli_num_rows($check) > 0)
        {
            //Removing like
            $deleteLike = "DELETE FROM `video_likes` WHERE `uId`= '$uId' AND `video_article_id` = '$videoArticleId'";
            $delete = mysqli_query($conn,$deleteLike);

            if($delete)
            {
                //Decrementing like 
                $updateArticleLike = "UPDATE `video_articles` SET `likes` = `likes`-1 WHERE `id` = '$videoArticleId'";
                $update = mysqli_query($conn,$updateArticleLike);

                if($update)
                {
                    $response['status_code'] = 0;
                    $response['message'] = 'Video article like updated successfully';
                    
                }
                else
                {
                    $response['status_code'] = 4;
                    $response['message'] = 'Some error occurred while updating video article likes';
                }

            }
            else
            {
                $response['status_code'] = 4;
                $response['message'] = 'Some error occurred while deleting like';
                
            }
        }
        else
        {
            //Adding like
            $createLike = "INSERT INTO `video_likes` (`id`, `uId`, `video_article_id`, `date`) VALUES (NULL, '$uId', '$videoArticleId', current_timestamp())";
            $create = mysqli_query($conn,$createLike);

            if($create)
            {
                //Incrementing likes
                $updateArticleLike = "UPDATE `video_articles` SET `likes` = `likes`+1 WHERE `id` = '$videoArticleId'";
                $update = mysqli_query($conn,$updateArticleLike);

                if($update)
                {
                    $response['status_code'] = 0;
                    $response['message'] = 'Video article like updated successfully';
                    
                }
                else
                {
                    $response['status_code'] = 4;
                    $response['message'] = 'Some error occurred while updating video article likes';
                }
            }
            else
            {
                $response['status_code'] = 4;
                $response['message'] = 'Some error occurred while creating video article like';
                
            }
        }


    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while checking video article like';
        
    }


    echo json_encode($response);


?>