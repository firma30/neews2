<?php


session_start();

require("../config/conn.php");
require("../functions.php");

// Define the API endpoint
header('Content-Type: application/json');

$currentYear = date('Y');
$sql = "SELECT COUNT(*) as count, MONTH(account_created) as month FROM `users` WHERE YEAR(account_created) = $currentYear GROUP BY MONTH(account_created)";
$query_run = mysqli_query($conn, $sql);

if (!$query_run) {
    die("Query execution failed: " . mysqli_error($conn));
}

$data = [];
$monthCounts = array_fill(1, 12, 0); // Initialize an array with all months set to 0 count

while ($row = mysqli_fetch_assoc($query_run)) {
    $month = (int)$row['month'];
    $count = (int)$row['count'];
    $monthCounts[$month] = $count;
}

// Get the month names
$monthNames = array(
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
);

// Prepare the response data
foreach ($monthCounts as $month => $count) {
    $data[] = array(
        'month' => $monthNames[$month - 1],
        'count' => $count
    );
}

mysqli_close($conn);




echo json_encode($data);



?>