<?php
session_start();
require("../config/conn.php");

require("../functions.php");



// Define the API endpoint

header('Content-Type: application/json');


$table_name = $_GET['table_name'];

$coloumn_name = $_GET['coloumn_name'];


$sql = "SELECT * FROM `$table_name`";

$query_run = mysqli_query($conn, $sql);



$response = array();



if ($query_run) {

    if (mysqli_num_rows($query_run) > 0) {

        $userCount = 0;



        while ($row = mysqli_fetch_assoc($query_run)) {

            $date = date('d/m/y');

            $userDate = date_format(date_create($row[$coloumn_name]), "d/m/y");



            if ($date == $userDate) {

                $userCount++;
            }
        }



        $response['count'] = $userCount;
    } else {

        $response['count'] = 0;
    }
} else {

    $response['error'] = 'Some Error Occurred!';
}



// Close the database connection

$conn->close();



// Set the response header as JSON

header('Content-Type: application/json');



// Return the response as JSON

echo json_encode($response);
