<?php
header('Content-Type: application/json');

// Check if reservation ID is provided
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
    exit();
}

// Get reservation ID from the request
$reservationId = intval($_GET['id']);

// Fetch the reservation details from the request
$reservationDate = '';
$startTime = '';
$endTime = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    $reservationDate = isset($data['reservation_date']) ? $data['reservation_date'] : '';
    $startTime = isset($data['start_time']) ? $data['start_time'] : '';
    $endTime = isset($data['end_time']) ? $data['end_time'] : '';
}

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reservadb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Set the reservation status to "In Review"
$status = "In Review";

// Prepare the SQL statement to update reservation details and status
$stmt = $conn->prepare("UPDATE reservations SET reservation_status = ?, reservation_date = ?, start_time = ?, end_time = ? WHERE id = ?");
$stmt->bind_param("ssssi", $status, $reservationDate, $startTime, $endTime, $reservationId);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['success' => 'Reservation updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error updating reservation: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>
