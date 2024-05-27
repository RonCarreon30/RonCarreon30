<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    include 'config.php';

    $course_id = $_POST['course'];
    $year_section_id = $_POST['yearSection'];

    // Prepare statement for inserting into course_year_section
    $stmt = $conn->prepare("INSERT INTO course_year_section (course_id, year_section_id) VALUES (?, ?)");

    // Initialize success and error messages
    $successMessage = "Year level and section assigned to course successfully.";
    $errorMessage = "";

    // Bind parameters and execute the statement
    $stmt->bind_param("ii", $course_id, $year_section_id);
    if (!$stmt->execute()) {
        $errorMessage .= "Error assigning year level and section: " . $stmt->error . ". ";
    }

    // Close statement
    $stmt->close();

    // Close connection
    $conn->close();

    // Redirect back with success or error messages
    $redirectUrl = $_SERVER['HTTP_REFERER'];
    if (!empty($successMessage)) {
        $redirectUrl .= "?success=" . urlencode($successMessage);
    }
    if (!empty($errorMessage)) {
        $redirectUrl .= "&error=" . urlencode($errorMessage);
    }
    header("Location: " . $redirectUrl);
    exit;
}
?>
