<?php

require("../config/conn.php");

    $uId = $_POST['uId'];
    $categoryName = $_POST['categoryName'];
    $categoryId = $_POST['categoryId'];

    $createUserTopic = "INSERT INTO `user_intrest` (`id`, `uId`, `category_name`, `category_id`) VALUES (NULL, '$uId', '$categoryName', '$categoryId')";

    $create = mysqli_query($conn,$createUserTopic);

    if($create)
    {
        $response['status_code'] = 0;
        $response['message'] = 'User intrest added successfully';
    }
    else 
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while adding user intrest';
    }

    echo json_encode($response);

?>