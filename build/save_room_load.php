<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

// Check if the user has the required role
if ($_SESSION['role'] !== 'Dept. Head') {
    header("Location: index.html");
    exit();
}

// Include the database configuration file
include 'config.php';

// Initialize variables from the POST data
$class_id = $_POST['class'] ?? null;
$academicYear = $_POST['academicYear'] ?? null;
$semester = $_POST['semester'] ?? null;
$course = $_POST['course'] ?? null;
$yearSection = $_POST['yearSection'] ?? null;
$subject = $_POST['subject'] ?? null;
$faculty = $_POST['faculty'] ?? null;
$startTime = $_POST['startTime'] ?? null;
$endTime = $_POST['endTime'] ?? null;
$day = $_POST['day'] ?? null;
$room_type = $_POST['room_type'] ?? null;
$building = $_POST['building'] ?? null;
$room = $_POST['room'] ?? null;

// Validate inputs
$errors = [];
if (!$class_id) $errors[] = "Class is required.";
if (!$academicYear) $errors[] = "Academic Year is required.";
if (!$semester) $errors[] = "Semester is required.";
if (!$course) $errors[] = "Course is required.";
if (!$yearSection) $errors[] = "Year & Section is required.";
if (!$subject) $errors[] = "Subject is required.";
if (!$faculty) $errors[] = "Faculty is required.";
if (!$startTime) $errors[] = "Start Time is required.";
if (!$endTime) $errors[] = "End Time is required.";
if (!$day) $errors[] = "Day is required.";
if (!$room_type) $errors[] = "Room Type is required.";
if (!$building) $errors[] = "Building is required.";
if (!$room) $errors[] = "Room is required.";

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $referrer = $_SERVER['HTTP_REFERER']; // Get the referrer URL
    header("Location: $referrer");
    exit();
}

// Prepare the UPDATE statement
$stmt = $conn->prepare("
    UPDATE classes
    SET academic_year_id = ?, semester_id = ?, course_id = ?, year_section_id = ?, subject_id = ?, faculty_id = ?, time_start = ?, time_end = ?, day = ?, room_type = ?, building = ?, room_id = ?
    WHERE id = ?
");

$stmt->bind_param("iiiiisssssssi", $academicYear, $semester, $course, $yearSection, $subject, $faculty, $startTime, $endTime, $day, $room_type, $building, $room, $class_id);

// Execute the statement
if ($stmt->execute()) {
    $_SESSION['success'] = "Class successfully updated!";
    $referrer = $_SERVER['HTTP_REFERER']; // Get the referrer URL
    header("Location: $referrer");
    exit();
} else {
    $_SESSION['errors'] = ["There was an error updating the class. Please try again."];
    $referrer = $_SERVER['HTTP_REFERER']; // Get the referrer URL
    header("Location: $referrer");
    exit();
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
