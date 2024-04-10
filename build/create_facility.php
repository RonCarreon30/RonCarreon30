<?php
// create_Facility.php

// Validate input data (e.g., ensure required fields are present)
if (!isset($_POST['facilityName'], $_POST['building'], $_POST['status'], $_POST['descri'])) {
    // Handle missing fields
    echo json_encode(array('success' => false, 'message' => 'Missing required fields.'));
    exit();
}

// Sanitize input data to prevent SQL injection and other attacks
$facilityName = htmlspecialchars($_POST['facilityName']);
$building = htmlspecialchars($_POST['building']);
$fStatus = htmlspecialchars($_POST['status']);
$descri = htmlspecialchars($_POST['descri']);

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
$stmt = $conn->prepare("INSERT INTO facilities (facility_name, building, status, descri) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $facilityName, $building, $fStatus, $descri);

// Execute SQL statement to insert a new facility
if ($stmt->execute()) {
    // Close prepared statement
    $stmt->close();

    // Close database connection
    $conn->close();

    // Redirect back to facilityMngmnt.php with success parameter
    header("Location: facilityMngmnt.php?success=true");
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
