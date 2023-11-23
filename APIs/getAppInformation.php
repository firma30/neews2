<?php

require("../config/conn.php");

    $fetchPrivacyPolicy ="SELECT * FROM `app_information` WHERE `type` = 'privacy_policy' LIMIT 1";
    $getPrivacyPolicy = mysqli_query($conn,$fetchPrivacyPolicy);

    $fetchAboutUs = "SELECT * FROM `app_information` WHERE `type` = 'about_us' LIMIT 1";
    $getAboutUs = mysqli_query($conn,$fetchAboutUs);

    if($getPrivacyPolicy && $getAboutUs)
    {
        while($row = $getPrivacyPolicy->fetch_assoc())
        {
            $response['privacy_policy'] = $row['information'];
        }

        while($row = $getAboutUs->fetch_assoc())
        {
            $response['about_us'] = $row['information'];
        }

        $response['status_code'] = 0;
        $response['message'] = 'App information fetched successfully';
        
    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred while fetching app information';
    }

    echo json_encode($response);



?>