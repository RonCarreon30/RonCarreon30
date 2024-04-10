<?php
// Include your database connection file here
include 'config.php';

// Get the building name from the request
$buildingName = $_GET['building'];

// Query to select rooms associated with the building
$sql = "SELECT room_number FROM rooms WHERE building = '$buildingName'";

// Execute the query
$result = $conn->query($sql);

// Initialize an empty array to store room numbers
$roomNumbers = [];

// Check if there are rows returned
if ($result->num_rows > 0) {
    // Loop through each row to fetch room numbers
    while ($row = $result->fetch_assoc()) {
        // Add room number to the array
        $roomNumbers[] = $row['room_number'];
    }
}

// Close the database connection
$conn->close();

// Return room numbers as JSON
header('Content-Type: application/json');
echo json_encode($roomNumbers);
?>
