<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}

if (isset($_GET['inputText']) && !empty(trim($_GET['inputText']))) {
    $email = $_SESSION['email'];
    $word = trim($_GET['inputText']);

    // Connect to DB
    $conn = new mysqli("localhost", "textify", "textify123", "textify1");

    echo "connecting to DB...<br>";

    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error;
        die("Connection failed: " . $conn->connect_error);
    }
    echo "here";
    $stmt = $conn->prepare("INSERT INTO words (word, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $word, $email);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "Done";

    // Preserve the original GET parameters and redirect to result.html
    $query = http_build_query($_GET);
    header("Location: result.html?$query");
    exit();
} else {
    echo "No word submitted.";
}
