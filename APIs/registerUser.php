<?php

require("../config/conn.php");

$uId = $_POST['uId'];
$userName = $_POST['user_name'];
$email = $_POST['email'];
$password = $_POST['password'];
$language = $_POST['language'];
$verified = $_POST['verified'];
$signupVia = $_POST['sign_up_via'];
$signedIn = $_POST['signed_in'];

$checkUser = "SELECT * FROM `users` WHERE `uId` = '$uId'";
$check = mysqli_query($conn, $checkUser);

if ($check) {
    if (mysqli_num_rows($check) > 0) {
        $response['status_code'] = 1;
        $response['message'] = 'User already exist';
    } else {
        $registerUser = "INSERT INTO `users` (`id`, `uId`, `user_name`, `email`, `password`, `language`, `verified`, `sign_up_via`, `account_created`, `signed_in`, `last_active`) VALUES (NULL, '$uId', '$userName', '$email', '$password', '$language', '$verified', '$signupVia', current_timestamp(), '$signedIn', current_timestamp())";

        $register = mysqli_query($conn, $registerUser);

        if ($register) {
            $response['status_code'] = 0;
            $response['message'] = 'User registered successfully';
        } else {
            $response['status_code'] = 4;
            $response['message'] = 'Some error occurred while creating user';
        }
    }
} else {
    $response['status_code'] = 4;
    $response['message'] = 'Error occurred while checking user details';
}



echo json_encode($response);
