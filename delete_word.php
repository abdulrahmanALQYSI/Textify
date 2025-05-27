<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_POST['word'])) {
    header("Location: main.php");
    exit();
}

$email = $_SESSION['email'];
$word = $_POST['word'];

// Connect to the database
$conn = new mysqli("localhost", "textify", "textify123", "textify1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete the word that belongs to the logged-in user
$stmt = $conn->prepare("DELETE FROM words WHERE word = ? AND email = ?");
$stmt->bind_param("ss", $word, $email);
$stmt->execute();
$stmt->close();
$conn->close();

// Redirect back to the main page
header("Location: main.php");
exit();
