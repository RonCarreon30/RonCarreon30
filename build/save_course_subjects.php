<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    include 'config.php';

    $course_id = $_POST['course'];
    $subject_ids = $_POST['subjects'];

    // Prepare statement for inserting into course_subjects
    $stmt = $conn->prepare("INSERT INTO course_subjects (course_id, subject_id) VALUES (?, ?)");

    // Initialize success and error messages
    $successMessage = "Subjects assigned to course successfully.";
    $errorMessage = "";

    foreach ($subject_ids as $subject_id) {
        // Bind parameters and execute the statement
        $stmt->bind_param("ii", $course_id, $subject_id);
        if (!$stmt->execute()) {
            $errorMessage .= "Error assigning subject ID $subject_id: " . $stmt->error . ". ";
        }
    }

    // Close statement and connection
    $stmt->close();
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
