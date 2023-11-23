<?php
// (1) Include library JWT dan Dotenv
require_once('../vendor/autoload.php');

use Firebase\JWT\JWT;
use Dotenv\Dotenv;

// (2) Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . './..');
$dotenv->load();

function verifyToken($conn)
{
  // Ambil token dari header permintaan
  $token = null;
  $headers = apache_request_headers();
  if (isset($headers['Authorization'])) {
    $token = explode(" ", $headers['Authorization'])[1];
  }

  if (!$token) {
    http_response_code(401); // Unauthorized
    echo json_encode(['message' => 'Token not provided']);
    exit();
  }

  try {
    // Verifikasi token
    $decoded = JWT::decode($token, $_ENV['ACCESS_TOKEN_SECRET']);
    return $decoded;
  } catch (Exception $e) {
    http_response_code(401); // Unauthorized
    echo json_encode(['message' => 'Token invalid']);
    exit();
  }
}

require("../config/conn.php");
require("../functions.php");


$decoded_token = verifyToken($conn);

$user_id = $decoded_token->user_id;
$email = $decoded_token->email;

$sql = "SELECT * FROM `articles`";
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
