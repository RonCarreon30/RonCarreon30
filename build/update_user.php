<?php
session_start();

// Check if user is logged in and has the required role
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Registrar')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if ($input) {
    $userId = $input['userId'];
    $firstName = $input['firstName'];
    $lastName = $input['lastName'];
    $email = $input['email'];
    $department = $input['department'];
    $userRole = $input['userRole'];

    $servername = "localhost";
    $username = "root";
    $password = ""; // Change this if you have set a password for your database
    $dbname = "reservadb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit();
    }

    $sql = "UPDATE users SET first_name=?, last_name=?, email=?, department=?, userRole=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $firstName, $lastName, $email, $department, $userRole, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'User update failed']);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}
?>
