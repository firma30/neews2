<?php
require_once('./vendor/autoload.php');

use Firebase\JWT\JWT;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set Content-Type for JSON response
header('Content-Type: application/json');

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    exit();
}

// Validate data format
$json = file_get_contents('php://input');
$input = json_decode($json);

if (!isset($input->email) || !isset($input->password)) {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'Invalid data format']);
    exit();
}

// Authenticate user (dummy data)
$user = [
    'email' => 'anekapanduan@gmail.com',
    'hashed_password' => password_hash('admin123', PASSWORD_DEFAULT), // Use password hashing
    'user_id' => 1 // Example user ID
];

if ($input->email !== $user['email'] || !password_verify($input->password, $user['hashed_password'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['message' => 'Email or password incorrect']);
    exit();
}

// Generate access token
$expired_time = time() + (15 * 60);
$payload = [
    'user_id' => $user['user_id'], // Include user ID
    'email' => $input->email,
    'exp' => $expired_time
];

$access_token = JWT::encode($payload, $_ENV['ACCESS_TOKEN_SECRET'], 'HS256');

// Display access token and expiration time in JSON response
echo json_encode([
    'accessToken' => $access_token,
    'expiry' => date("Y-m-d\TH:i:sP", $expired_time)

]);

// Check expiration time on the server side
if ($payload['exp'] <= time()) {
    http_response_code(401); // Unauthorized
    echo json_encode(['message' => 'Token expired']);
    exit();
}

// Extend expiration time (1 hour)
$payload['exp'] = time() + (60 * 60);
$refresh_token = JWT::encode($payload, $_ENV['REFRESH_TOKEN_SECRET'], 'HS256');

// Save refresh token in an HTTP-only cookie
setcookie('refreshToken', $refresh_token, $payload['exp'], '', '', false, true);
