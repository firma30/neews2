<?php
session_start();
require("../config/conn.php");
require("../functions.php");

// Define the API endpoint
header('Content-Type: application/json');
$sql = "SELECT * FROM `user_intrest`";
$query_run = mysqli_query($conn, $sql);
if ($query_run) {
    $categories = array();

    // Retrieve all categories from the database
    $categories_query = mysqli_query($conn, "SELECT * FROM `categories`");
    while ($row = mysqli_fetch_array($categories_query)) {
        $category = strtolower(trim($row['category_name']));
        $categories[$category] = 0; // Initialize count as 0 for each category
    }
    while ($row = mysqli_fetch_array($query_run)) {
        $userInterests = $row['category_name'];

        // Split the user interests into an array
        $interests = explode(',', $userInterests);

        // Iterate through each interest
        foreach ($interests as $interest) {

            // Trim leading/trailing whitespaces and convert to lowercase for case-insensitive matching
            $interest = strtolower(trim($interest));

            // Check if the interest is in the categories array
            if (isset($categories[$interest])) {

                // Increment the count if it exists
                $categories[$interest]++;
            }
        }
    }

    // Prepare the response data
    $response = array();
    foreach ($categories as $category => $count) {
        $response[] = array(
            'category' => $category,
            'count' => $count
        );
    }
    echo json_encode($response);
} else {

    // Return an error message if the query fails
    $response = array('error' => 'Failed to fetch data.');
    echo json_encode($response);
}

// Close the database connection
mysqli_close($conn);
