<?php





session_start();



require("../config/conn.php");

require("../functions.php");



// Define the API endpoint

header('Content-Type: application/json');







    $currentMonth = date('m');

    $year = date('Y');

    $daysInMonth = date('t');



    $data = [];



    // Initialize user counts for each day to 0

    for ($day = 1; $day <= $daysInMonth; $day++) {

        $data[$day] = 0;

    }



    $sql = "SELECT DATE_FORMAT(account_created, '%d') AS day, COUNT(*) AS count FROM `users` WHERE MONTH(account_created) = $currentMonth AND YEAR(account_created) = $year GROUP BY day";

    $result = mysqli_query($conn, $sql);



    if ($result) {

        while ($row = mysqli_fetch_assoc($result)) {

            $day = $row['day'];

            $count = $row['count'];

            $data[$day] = $count;

        }



        // Close the database connection

        mysqli_close($conn);



    

    } else {

        // Handle database query error

        mysqli_close($conn);

        $data = false;

    }









if ($data !== false) {

    echo json_encode($data);

} else {

    // Handle error response

    header('HTTP/1.1 500 Internal Server Error');

    echo 'An error occurred while retrieving user counts.';

}







?>