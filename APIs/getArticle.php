<?php

require("../config/conn.php");

$articleId = $_POST['article_id'];
$uId = $_POST['uId'];
// $categoryId;
// $articleId;

$fetchArticle = "SELECT * FROM `articles` WHERE `id` = '$articleId'";
$getArticle = mysqli_query($conn, $fetchArticle);

if ($getArticle) {
    if (mysqli_num_rows($getArticle) > 0) {

        while ($row = $getArticle->fetch_assoc()) {
            $response['article'] = $row;
            $articleId = $row['id'];
            $categoryId = $row['category_id'];
        }


        //Fetching category
        $fetchCategory = "SELECT * FROM `categories` WHERE `id` = '$categoryId'";
        $getCategory = mysqli_query($conn, $fetchCategory);

        if ($getCategory) {
            if (mysqli_num_rows($getCategory) > 0) {
                $response['status_code'] = 0;
                $response['message'] = 'Article fetched successfully';
                while ($row = $getCategory->fetch_assoc()) {
                    $response['category'] = $row;
                }
            } else {
                $response['status_code'] = 1;
                $response['message'] = 'Category not available';
            }
        } else {
            $response['status_code'] = 4;
            $response['message'] = 'Some error occurred while fetching category';
        }

        if (trim($uId) != '') {
            //Checking user like
            $checkUserLike = "SELECT * FROM `likes` WHERE `uId` = '$uId' AND `article_id` = '$articleId'";
            $checkLike = mysqli_query($conn, $checkUserLike);

            if ($checkLike) {
                if (mysqli_num_rows($checkLike) > 0) {
                    $response['is_liked'] = 1;
                } else {
                    $response['is_liked'] = 0;
                }
            }

            //Checking user save
            $checkUserSave = "SELECT * FROM `saves` WHERE `uId` = '$uId' AND `article_id` = '$articleId'";
            $checkSave = mysqli_query($conn, $checkUserSave);

            if ($checkSave) {
                if (mysqli_num_rows($checkSave) > 0) {
                    $response['is_saved'] = 1;
                } else {
                    $response['is_saved'] = 0;
                }
            }

            if ($getCategory && $checkLike && $checkSave) {
                $response['status_code'] = 0;
                $response['message'] = 'Article fetched successfully';
            } else {
                $response['status_code'] = 4;
                $response['message'] = 'Some error occured while fetching article data';
            }
        } else {
            $response['is_liked'] = 0;
            $response['is_saved'] = 0;
            $response['status_code'] = 0;
            $response['message'] = 'Article fetched successfully';
        }
    } else {
        $response['status_code'] = 1;
        $response['message'] = 'Article not available';
    }
} else {
    $response['status_code'] = 4;
    $response['message'] = 'Some error occurred while fetching article';
}

echo json_encode($response);
