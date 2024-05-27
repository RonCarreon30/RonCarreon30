<?php
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
    $faculty_name = sanitize_input($_POST['facultyName']);
    $department = sanitize_input($_POST['department']);
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0; // Assuming 'id' is passed when updating a record

    // Validate input data
    if (!empty($faculty_name) && !empty($department)) {
        if ($id > 0) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE faculty SET faculty_name = ?, department = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->bind_param("ssi", $faculty_name, $department, $id);
        } else {
            // Insert new record
            $stmt = $conn->prepare("INSERT INTO faculty (faculty_name, department) VALUES (?, ?)");
            $stmt->bind_param("ss", $faculty_name, $department);
        }

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=Faculty record saved successfully");
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
