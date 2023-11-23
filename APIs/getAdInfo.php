<?php

require("../config/conn.php");

    $fetchAdStatus = "SELECT * FROM `app_settings` WHERE `id` = 1 ";
    $getAdStatus = mysqli_query($conn,$fetchAdStatus);

    $adEnabled;

    if($getAdStatus)
    {
        //Fetch Ad Staus
        while($row = $getAdStatus->fetch_assoc())
        {
            $adEnabled = $row['value'];
            $response['ad_enabled'] = $adEnabled;
        }

        $response['status_code'] = 0;
        $response['message'] = 'Successful';
        
        //Checking Ad Status

        if($adEnabled == 1)
        {
            //Fetching Ad Ids

            // 1.SMALL Banner Ad Id (ANDROID)
            $fetchBannerAdId = "SELECT * FROM `ad_info` WHERE `id` = 1 AND `platform` = 'ANDROID' LIMIT 1";
            $getBannerAdId = mysqli_query($conn,$fetchBannerAdId);

            if($getBannerAdId)
            {
                while($row = $getBannerAdId->fetch_assoc())
                {
                    $response['android_banner_ad_id'] = $row['value'];
                }
                
            }

            //  1.1 Banner Ad Id (IOS)
            $fetchBannerAdId = "SELECT * FROM `ad_info` WHERE `id` = 2 AND `platform` = 'IOS' LIMIT 1";
            $getBannerAdId = mysqli_query($conn,$fetchBannerAdId);

            if($getBannerAdId)
            {
                while($row = $getBannerAdId->fetch_assoc())
                {
                    $response['ios_banner_ad_id'] = $row['value'];
                }
                
            }
        }

    }
    else
    {
        $response['status_code'] = 4;
        $response['message'] = 'Some error occurred';   
    }

    echo json_encode($response);

?>