<?php

require("../config/conn.php");

    $uId = $_POST['uId'];

    $updateUserVerification = "UPDATE `users` SET `verified` = '1', `signed_in`= '1' WHERE `uId`='$uId'";
    $update = mysqli_query($conn,$updateUserVerification);

    if($update)
    {
        $response['status_code'] = 0;
        $response['message'] = 'User verified successfully';
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while verifying user';
    }

    echo json_encode($response);


?>