<?php
// Include database connection
require_once 'conn.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Query to fetch unique start and stop locations from the route table
$sql = "SELECT start, stop FROM route";
$result = $conn->query($sql);

$locations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Add both start and stop locations to the array
        $locations[] = $row['start'];
        $locations[] = $row['stop'];
    }
}

// Remove duplicates
$locations = array_unique($locations);

// Close the database connection
$conn->close();

// Set the content type to JSON and output the data
header('Content-Type: application/json');
echo json_encode(array_values($locations)); // Return the locations as a JSON array
?>
