<?php
// Database Connection Settings

// Hostname or IP address of your MySQL server
define('DB_HOST', 'localhost');

// MySQL username
define('DB_USERNAME', 'root');

// MySQL password
define('DB_PASSWORD', '');

// Name of the database
define('DB_NAME', 'farm2');

// Establish a connection to the database
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set to UTF-8
$conn->set_charset("utf8");

// Function to close the database connection
function close_connection() {
    global $conn;
    $conn->close();
}
?>
