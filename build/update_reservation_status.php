<?php
// Check if reservation ID and status are provided
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    http_response_code(400);
    exit('Missing parameters');
}

// Get reservation ID and status from the request
$reservationId = $_GET['id'];
$status = $_GET['status'];

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reservadb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    exit('Connection failed: ' . $conn->connect_error);
}

// Update reservation status in the database
$stmt = $conn->prepare("UPDATE reservations SET reservation_status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $reservationId);

if ($stmt->execute()) {
    http_response_code(200);
    echo "Reservation status updated successfully";
} else {
    http_response_code(500);
    echo "Error updating reservation status: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
