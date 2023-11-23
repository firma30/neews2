<?php

session_start();

require("../config/conn.php");
require("../functions.php");

// Define the API endpoint
header('Content-Type: application/json');

$sql = "SELECT * FROM `video_articles`";
$result = mysqli_query($conn, $sql);
$rows = array();
$sr_no = 1;
while ($row = mysqli_fetch_assoc($result)) {
  $row['sr_no'] = $sr_no;
  $rows[] = $row;
  $sr_no++;
}

echo json_encode($rows);
mysqli_close($conn);
