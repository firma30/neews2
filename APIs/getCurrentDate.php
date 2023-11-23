<?php

    $currentDate = date('Y-m-d H:i:s');
    $response['date'] = $currentDate;
    echo json_encode($response);

?>