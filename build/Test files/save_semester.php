<?php
    // Database connection
    include 'config.php';

// Create connection
$conn = new mysqli($servername, $username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input data
    $semester_name = sanitize_input($_POST['semesterName']);
    $start_date = sanitize_input($_POST['startDate']);
    $end_date = sanitize_input($_POST['endDate']);
    $status = sanitize_input($_POST['semStatus']);
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0; // Assuming 'id' is passed when updating a record

    // Validate input data
    if (!empty($semester_name) && !empty($start_date) && !empty($end_date) && !empty($status)) {
        if ($id > 0) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE semesters SET semester_name = ?, start_date = ?, end_date = ?, status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->bind_param("ssssi", $semester_name, $start_date, $end_date, $status, $id);
        } else {
            // Insert new record
            $stmt = $conn->prepare("INSERT INTO semesters (semester_name, start_date, end_date, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $semester_name, $start_date, $end_date, $status);
        }

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=New course created successfully");
            exit;
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=" . $stmt->error);
            exit;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}

// Close the connection
$conn->close();
?>
