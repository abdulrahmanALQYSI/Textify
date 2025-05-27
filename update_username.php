<?php
session_start();

// Ensure the user is logged in and the form was submitted correctly
if (!isset($_SESSION['email']) || !isset($_POST['new_username'])) {
    header("Location: main.php");
    exit();
}

// Get the new username and sanitize it
$newUsername = trim($_POST['new_username']);
$email = $_SESSION['email'];

// Check if username is not empty
if (empty($newUsername)) {
    header("Location: main.php");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "textify", "textify123", "textify1");

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the update statement
$stmt = $conn->prepare("UPDATE users SET username = ? WHERE email = ?");
$stmt->bind_param("ss", $newUsername, $email);
$stmt->execute();
$stmt->close();
$conn->close();

// Update session value
$_SESSION['username'] = $newUsername;

// Redirect back to main page
header("Location: main.php");
exit();
