<?php

require("../config/conn.php");

    $uId = $_POST['uId'];
    $field = $_POST['field'];
    $data = $_POST['data'];

    //Get update user data
    $updateUserData = "UPDATE `users` SET `$field` = '$data' WHERE `uId` = '$uId'";

    $update = mysqli_query($conn,$updateUserData);

    if($update)
    {
        $response['status_code'] = 0;
        $response['message'] = 'User updated successfully';
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while updating user';
    }

    echo json_encode($response);
    



?>