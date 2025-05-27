<?php
// Simple test file to check if PHP is working
header('Content-Type: text/plain');

echo "PHP is working!\n";
echo "Current time: " . date('Y-m-d H:i:s') . "\n";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST request received\n";
    echo "Email: " . (isset($_POST['email']) ? $_POST['email'] : 'not set') . "\n";
    echo "Password: " . (isset($_POST['password']) ? $_POST['password'] : 'not set') . "\n";
} else {
    echo "Request method: " . $_SERVER['REQUEST_METHOD'] . "\n";
}

// Test database connection using mysqli
$conn = mysqli_connect('localhost', 'root', 'textify123', 'textify');

if (!$conn) {
    echo "Database connection: FAILED - " . mysqli_connect_error() . "\n";
} else {
    echo "Database connection: SUCCESS\n";

    // Test if users table exists
    $result = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
    if (mysqli_num_rows($result) > 0) {
        echo "Users table: EXISTS\n";

        // Count users
        $count_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
        $count = mysqli_fetch_assoc($count_result);
        echo "Number of users: " . $count['count'] . "\n";
    } else {
        echo "Users table: NOT FOUND\n";
    }

    mysqli_close($conn);
}
