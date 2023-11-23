<?php

ob_start();

// Host Name Here
$hostName = "localhost";

// User Name Here
$username = "root";

// Password Here
$password = "";

// Database Name 
$dbName = "supergoa_panduan";
$conn = mysqli_connect($hostName, $username, $password, $dbName) or die("Error :- Connection Failed");
