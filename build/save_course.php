<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseName = $_POST['courseName'];
    $courseCode = $_POST['courseCode'];
    $courseCollege = $_POST['courseCollege'];

    // Validate inputs
    if (empty($courseName) || empty($courseCode)) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=Course name and course code are required.");
        exit;
    }

    // Database connection
    include 'config.php';

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code, course_college) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $courseName, $courseCode, $courseCollege);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=New course created successfully");
        exit;
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=" . $stmt->error);
        exit;
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
