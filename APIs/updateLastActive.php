<?php

require("../config/conn.php");

    $uId = $_POST['uId'];

    $updateLastActive = "UPDATE `users` SET `last_active` = current_timestamp() WHERE `users`.`uId` = '$uId';";

    if($updateLastActive)
    {
        $response['status_code'] = 0;
        $response['message'] = 'Updated successfully';
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while updating user last active';
    }

    echo json_encode($response);


?>