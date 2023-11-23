<?php





session_start();



require("../config/conn.php");

require("../functions.php");



// Define the API endpoint

header('Content-Type: application/json');







$table_name = $_GET['table_name'];





// Retrieve the number of rows in the table

$sql = "SELECT COUNT(*) as count FROM `$table_name`";

$result = $conn->query($sql);



if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    $count = $row['count'];

} else {

    $count = 0;

}







// Return the count as JSON response

$response = array('count' => $count, 'table_name' => $table_name);

echo json_encode($response);



// Close the database connection

$conn->close();
