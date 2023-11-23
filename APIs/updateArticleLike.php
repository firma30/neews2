<?php

require("../config/conn.php");

    $uId = $_POST['uId'];
    $articleId = $_POST['article_id'];

    $checkArticle = "SELECT * FROM `likes` WHERE `uId`= '$uId' AND `article_id` = '$articleId'";
    $check = mysqli_query($conn,$checkArticle);

    if($checkArticle)
    {
        if(mysqli_num_rows($check) > 0)
        {
            //Removing like
            $deleteLike = "DELETE FROM `likes` WHERE `uId`= '$uId' AND `article_id` = '$articleId'";
            $delete = mysqli_query($conn,$deleteLike);

            if($delete)
            {
                //Decrementing like 
                $updateArticleLike = "UPDATE `articles` SET `likes` = `likes`-1 WHERE `id` = '$articleId'";
                $update = mysqli_query($conn,$updateArticleLike);

                if($update)
                {
                    $response['status_code'] = 0;
                    $response['message'] = 'Article like updated successfully';
                    
                }
                else
                {
                    $response['status_code'] = 4;
                    $response['message'] = 'Some error occurred while updating article likes';
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
            $createLike = "INSERT INTO `likes` (`id`, `uId`, `article_id`, `date`) VALUES (NULL, '$uId', '$articleId', current_timestamp())";
            $create = mysqli_query($conn,$createLike);

            if($create)
            {
                //Incrementing likes
                $updateArticleLike = "UPDATE `articles` SET `likes` = `likes`+1 WHERE `id` = '$articleId'";
                $update = mysqli_query($conn,$updateArticleLike);

                if($update)
                {
                    $response['status_code'] = 0;
                    $response['message'] = 'Article like updated successfully';
                    
                }
                else
                {
                    $response['status_code'] = 4;
                    $response['message'] = 'Some error occurred while updating article likes';
                }
            }
            else
            {
                $response['status_code'] = 4;
                $response['message'] = 'Some error occurred while creating article like';
                
            }
        }
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while checking article like';
        
    }


    echo json_encode($response);


?>