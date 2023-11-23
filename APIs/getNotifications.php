<?php

require("../config/conn.php");

    $fetchNotifications = 'SELECT * FROM `notifications` ORDER BY `date` DESC';
    $fetchNotif = mysqli_query($conn,$fetchNotifications);

    if($fetchNotif)
    {
        if(mysqli_num_rows($fetchNotif) > 0)
        {
            $response['status_code'] = 0;
            $response['message'] = 'Notifications fetched successfully';

            while($row = $fetchNotif->fetch_assoc())
            {
                $response['notifications'][] = $row;
            }
            
        }
        else
        {
            $response['status_code'] = 1;
            $response['message'] = 'No articles found';
        }
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while fetching notifications';
    }

    echo json_encode($response);


?>