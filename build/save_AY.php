<?php
// Database connection settings
include_once 'config.php';

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
    $academic_year = sanitize_input($_POST['academicYear']);
    $start_date = sanitize_input($_POST['startDate']);
    $end_date = sanitize_input($_POST['endDate']);
    $status = sanitize_input($_POST['acadYearStatus']);
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0; // Assuming 'id' is passed when updating a record

    // Validate input data
    if (!empty($academic_year) && !empty($start_date) && !empty($end_date) && !empty($status)) {
        if ($id > 0) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE academic_years SET academic_year = ?, start_date = ?, end_date = ?, status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->bind_param("ssssi", $academic_year, $start_date, $end_date, $status, $id);
        } else {
            // Insert new record
            $stmt = $conn->prepare("INSERT INTO academic_years (academic_year, start_date, end_date, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $academic_year, $start_date, $end_date, $status);
        }

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
