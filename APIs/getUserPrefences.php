<?php

require("../config/conn.php");

    $uId = $_POST['uId'];

    $fetchUserPrefences = "SELECT *
    FROM `categories`
    JOIN user_intrest ON user_intrest.category_id = categories.id
    WHERE user_intrest.uId = '$uId'";

    $getUserIntrestCategories = mysqli_query($conn,$fetchUserPrefences);

    if($getUserIntrestCategories)
    {
        if(mysqli_num_rows($getUserIntrestCategories) > 0)
        {
            $response['status_code'] = 0;
            $response['message'] = 'Datav fetched successfully';
            
            while($row = $getUserIntrestCategories->fetch_assoc())
            {
                $response['user_prefences'][] = $row;
            }
            
        }
        else
        {
            $response['status_code'] = 1;
            $response['message'] = 'No categories selected';
            
        }
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while fetching user prefences';
    }


    echo json_encode($response);
