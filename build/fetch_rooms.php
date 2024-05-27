<?php
include 'config.php';

$building = $_POST['building'];

$sql = "SELECT room_id, room_number FROM rooms WHERE building = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $building);
$stmt->execute();
$result = $stmt->get_result();

$rooms = "<option value='' disabled selected>Select Room</option>";
while ($row = $result->fetch_assoc()) {
    $rooms .= "<option value='" . $row['room_id'] . "'>" . $row['room_number'] . "</option>";
}

echo json_encode(['rooms' => $rooms]);
?>
