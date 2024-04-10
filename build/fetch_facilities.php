<?php
// fetch_facilities.php

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Change this if you have set a password for your database
$dbname = "reservadb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch facilities from the database
$sql = "SELECT * FROM facilities";
$result = $conn->query($sql);

// Initialize an array to store facility data
$facilities = array();

if ($result->num_rows > 0) {
    // Fetch facility data row by row
    while ($row = $result->fetch_assoc()) {
        // Push facility data to the facilities array
        $facilities[] = $row;
    }
}

// Close database connection
$conn->close();

// Return facility data as JSON
echo json_encode(array('facilities' => $facilities));
?>
