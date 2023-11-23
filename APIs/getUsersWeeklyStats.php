<?php


session_start();

require("../config/conn.php");
require("../functions.php");

// Define the API endpoint
header('Content-Type: application/json');




// Get the current week number
$currentWeek = date('W');

// Initialize the result array
$result = array();

// Get the start and end dates of the current week
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));

// Get all the days of the current week
$days = array();
$day = $startOfWeek;

while ($day <= $endOfWeek) {
    $days[] = $day;
    $day = date('Y-m-d', strtotime($day . ' +1 day'));
}

// Query to fetch user count for each day of the current week
$sql = "SELECT DATE(account_created) AS date, COUNT(*) AS count
        FROM users
        WHERE DATE(account_created) BETWEEN '$startOfWeek' AND '$endOfWeek'
        GROUP BY DATE(account_created)";

$query_result = mysqli_query($conn, $sql);

if ($query_result) {
    // Loop through each day of the current week
    foreach ($days as $day) {
        // Initialize the count for the current day
        $count = 0;

        // Loop through the query result and update the count if the day exists
        while ($row = mysqli_fetch_assoc($query_result)) {
            $date = $row['date'];

            // Check if the day exists in the query result
            if ($date == $day) {
                $count = $row['count'];
                break;
            }
        }

        // Extract the day name from the date
        $dayName = date('l', strtotime($day));

        // Add the day and count to the result array
        $result[] = array(
            'day' => $dayName,
            'count' => $count
        );

        // Move the result pointer back to the beginning
        mysqli_data_seek($query_result, 0);
    }
}

// Close the database connection
mysqli_close($conn);

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($result);



?>