<?php

require("../config/conn.php");

    $uId = $_POST['uId'];
    $videoArticleId = $_POST['video_article_id'];

    //Get Article Likes
    $fetchVideoArticle = "SELECT * FROM `video_articles` WHERE `id`='$videoArticleId'";
    $getInfo = mysqli_query($conn,$fetchVideoArticle);

    if($getInfo)
    {
        if(mysqli_num_rows($getInfo) > 0)
        {
            $response['status_code'] = 0;
            $response['message'] = 'Video article fetched successfully';

            while($row=$getInfo->fetch_assoc())
            {
                $response['video_articles'] = $row;
            }

            $response['status_code'] = 0;
            $response['message'] = 'Video  article info fetched';
            
            //Check video like
            $checkUserLike = "SELECT * FROM `video_likes` WHERE `uId` = '$uId' AND `video_article_id` = '$videoArticleId'";
            $checkLike = mysqli_query($conn,$checkUserLike);

            if($checkLike)
            {
                if(mysqli_num_rows($checkLike) > 0)
                {
                    $response['is_liked'] = 1;
                }
                else
                {
                    $response['is_liked'] = 0;
                }
            }

            //Check user save
            $checkUserSave = "SELECT * FROM `video_saves` WHERE `uId` = '$uId' AND `video_article_id` = '$videoArticleId'";
            $checkSave = mysqli_query($conn,$checkUserSave);

            if($checkSave)
            {
                if(mysqli_num_rows($checkSave) > 0)
                {
                    $response['is_saved'] = 1;
                }
                else
                {
                    $response['is_saved'] =  0;
                }
            }
            
        }
        else
        {
            $response['status_code'] = 1;
            $response['message'] = 'No article available';
            
        }

    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while fetching article info';
        
    }

    echo json_encode($response);
