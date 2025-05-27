<?php

session_start();
// Set content type to prevent any output issues
header('Content-Type: text/plain');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$username = 'textify'; // Default MySQL username
$password = 'textify123';
$dbname = 'textify1';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Invalid request method';
    exit;
}

// Get form data
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$user_password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Validate input
if (empty($email) || empty($user_password)) {
    echo 'Please fill in all fields';
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Please enter a valid email address';
    exit;
}

// Create database connection using mysqli
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    echo 'Database connection error: ' . mysqli_connect_error();
    exit;
}

// Prepare SQL statement to find user by email
$email = mysqli_real_escape_string($conn, $email);
$sql = "SELECT email, password FROM users WHERE email = '$email' LIMIT 1";
$result = mysqli_query($conn, $sql);

// Check if query was successful
if (!$result) {
    echo 'Database query error: ' . mysqli_error($conn);
    mysqli_close($conn);
    exit;
}

// Check if user exists
if (mysqli_num_rows($result) == 0) {
    echo 'Email address not found';
    mysqli_close($conn);
    exit;
}

// Get user data
$user = mysqli_fetch_assoc($result);

// Verify password (plain text comparison)
if ($user_password !== $user['password']) {
    echo 'Incorrect password';
    mysqli_close($conn);
    exit;
}

// Login successful
echo 'success';

$_SESSION['email'] = $email;

// Close database connection
mysqli_close($conn);
