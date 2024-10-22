<?php

function connect()
{
    // Create a new MySQLi connection
    $conn = new mysqli("localhost", "root", "", "otrsphp");

    // Check if there is a connection error
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Establish connection
$conn = connect();

// Check if connection was successful
if (!$conn) {
    die("Database connection failed!");
}
?>
