<?php
// Set content type to prevent any output issues
header('Content-Type: text/plain');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$dbname = 'textify';
$username = 'root'; // Default MySQL username
$password = 'textify123';

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

try {
    // Create database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL statement to find user by email
    $stmt = $pdo->prepare("SELECT id, email, password, name FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Check if user exists
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo 'Email address not found';
        exit;
    }

    // Verify password (plain text comparison)
    if ($user_password !== $user['password']) {
        echo 'Incorrect password';
        exit;
    }

    // Login successful
    echo 'success';
} catch (PDOException $e) {
    // Handle database errors
    if ($e->getCode() == 1049) { // Database doesn't exist
        echo 'Database connection error: Database not found';
    } elseif ($e->getCode() == 1045) { // Access denied
        echo 'Database connection error: Access denied';
    } elseif ($e->getCode() == 2002) { // Can't connect to server
        echo 'Database connection error: Cannot connect to server';
    } elseif ($e->getCode() == 1146) { // Table doesn't exist
        echo 'Database error: Users table not found';
    } else {
        // Log the actual error for debugging (don't show to user)
        error_log("Login error: " . $e->getMessage());
        echo 'Database connection error. Please try again later';
    }
} catch (Exception $e) {
    // Handle other errors
    error_log("Login error: " . $e->getMessage());
    echo 'An unexpected error occurred. Please try again later';
}

// Close database connection
$pdo = null;
