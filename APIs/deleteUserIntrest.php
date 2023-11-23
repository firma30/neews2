<?php

require("../config/conn.php");

    $uId = $_POST['uId'];
    $id = $_POST['id'];

    $deleteIntrest = "DELETE FROM `user_intrest` WHERE `id`='$id' AND `uId`='$uId'";
    $delete = mysqli_query($conn,$deleteIntrest);

    if($delete)
    {
        $response['status_code'] = 0;
        $response['message'] = 'User intrest deleted';
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while deleting user intrest';
    }

    echo json_encode($response);

?>