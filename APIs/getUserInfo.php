<?php

require("../config/conn.php");

    $uId = $_POST['uId'];

    $fetchUserInfo = "SELECT * FROM `users` WHERE `uId` = '$uId' LIMIT 1";
    $getUser = mysqli_query($conn,$fetchUserInfo);

    if($getUser)
    {
        if(mysqli_num_rows($getUser) > 0 )
        {
            $response['status_code'] = 0;
            $response['message'] = 'User Information fetched successfully';
            
            while($row = $getUser->fetch_assoc())
            {
                $response['user_info'] = $row;
            }
            
        }
        else
        {
            $response['status_code'] = 1;
            $response['message'] = 'No user found';
        }
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while fetching user information';
    }

    echo json_encode($response);

?>