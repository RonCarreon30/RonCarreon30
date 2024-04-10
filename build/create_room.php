<?php
// create_room.php

// Validate input data (e.g., ensure required fields are present)
if (!isset($_POST['roomNumber'], $_POST['building'], $_POST['type'], $_POST['roomStatus'])) {
    // Handle missing fields
    echo json_encode(array('success' => false, 'message' => 'Missing required fields.'));
    exit();
}

// Sanitize input data to prevent SQL injection and other attacks
$roomNumber = htmlspecialchars($_POST['roomNumber']);
$building = htmlspecialchars($_POST['building']);
$type = htmlspecialchars($_POST['type']);
$roomStatus = htmlspecialchars($_POST['roomStatus']);

// Connect to the database
$servername = "localhost";
$username = "root";
$db_password = ""; // Change this if you have set a password for your database
$dbname = "reservadb";

// Create connection
$conn = new mysqli($servername, $username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement to insert a new facility into the database
$stmt = $conn->prepare("INSERT INTO rooms (room_number, building, room_type, room_status) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $roomNumber, $building, $type, $roomStatus);

// Execute SQL statement to insert a new facility
if ($stmt->execute()) {
    // Close prepared statement
    $stmt->close();

    // Close database connection
    $conn->close();

    // Redirect back to roomMngmnt.php with success parameter
    header("Location: roomMngmnt.php?success=true");
    exit();
} else {
    // Return error message as JSON response
    echo json_encode(array('success' => false, 'message' => 'Error adding facility: ' . $stmt->error));

    // Close prepared statement
    $stmt->close();

    // Close database connection
    $conn->close();
}
?>
