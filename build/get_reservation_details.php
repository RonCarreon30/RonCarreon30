<?php
$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "reservadb";
$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$reservationId = $_GET['id'];
$sql = "SELECT * FROM reservations WHERE id = $reservationId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(['error' => 'No reservation found']);
}

$conn->close();
?>
