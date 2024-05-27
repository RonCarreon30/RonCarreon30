<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header("Location: index.html");
    exit();
}

// Check if the user has the required role
if ($_SESSION['role'] !== 'Registrar') {
    // Redirect to a page indicating unauthorized access
    header("Location: index.html");
    exit();
}

// Include the database configuration file
include 'config.php';

// Initialize variables
$errors = [];
$classAY = $_POST['classAY'] ?? null;
$classSem = $_POST['classSem'] ?? null;
$classCourse = $_POST['classCourse'] ?? null;
$classYear_section = $_POST['classYear_section'] ?? null;
$classSubject = $_POST['classSubject'] ?? null;
$room_type = $_POST['room_type'] ?? null;  // Changed to 'room_type'

// Validate inputs
if (!$classAY) {
    $errors[] = "Academic Year is required.";
}
if (!$classSem) {
    $errors[] = "Semester is required.";
}
if (!$classCourse) {
    $errors[] = "Course is required.";
}
if (!$classYear_section) {
    $errors[] = "Year & Section is required.";
}
if (!$classSubject) {
    $errors[] = "Subject is required.";
}
if (!$room_type) {  // Changed to 'room_type'
    $errors[] = "Room Type is required.";
}

// If there are errors, redirect back to the form with error messages
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: academic_admin.php#classes-form");
    exit();
}

// Fetch course name, year_level, section, and course_college from the database based on the provided course_id
$sql = "SELECT cr.course_name, cr.course_college, ys.year_level, ys.section 
        FROM courses cr 
        JOIN year_section ys ON cr.id = ? AND ys.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $classCourse, $classYear_section);
$stmt->execute();
$stmt->bind_result($course_name, $course_college, $year_level, $section);
$stmt->fetch();
$stmt->close();

// If course name, year_level, section, or course_college is not found, set them to empty strings
$course_name = $course_name ?? '';
$course_college = $course_college ?? '';
$year_level = $year_level ?? '';
$section = $section ?? '';

// Combine course name, year_level, and section to create class_name
$class_name = $course_name . ' ' . $year_level . ' - ' . $section;

// Prepare and bind the insert statement
$stmt = $conn->prepare("INSERT INTO classes (academic_year_id, semester_id, course_id, year_section_id, subject_id, room_type, class_name, class_college) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iiiiisss", $classAY, $classSem, $classCourse, $classYear_section, $classSubject, $room_type, $class_name, $course_college);  // Changed to 'room_type'

// Execute the statement
if ($stmt->execute()) {
    // Success: Redirect back to the referrer page with a success message
    $_SESSION['success'] = "Class successfully saved!";
    $referrer = $_SERVER['HTTP_REFERER']; // Get the referrer URL
    header("Location: $referrer");
    exit();
} else {
    // Error: Redirect back to the referrer page with an error message
    $_SESSION['errors'] = ["There was an error saving the class. Please try again."];
    $referrer = $_SERVER['HTTP_REFERER']; // Get the referrer URL
    header("Location: $referrer");
    exit();
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
