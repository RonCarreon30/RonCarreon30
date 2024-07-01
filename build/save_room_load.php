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
    // Redirect back to the referrer and display error modal
    $_SESSION['errors'] = $errors;
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

// Check for overlapping schedules
$overlapQuery = "
    SELECT id 
    FROM classes 
    WHERE room_id = ? 
      AND id != ? 
      AND day = ? 
      AND NOT (
            (time_end <= ?) OR 
            (time_start >= ?)
          )
";

$overlapStmt = $conn->prepare($overlapQuery);
if (!$overlapStmt) {
    die("Preparation failed: " . $conn->error);
}
$overlapStmt->bind_param('iisss', $room, $class_id, $day, $startTime, $endTime);
$overlapStmt->execute();
$overlapResult = $overlapStmt->get_result();

if ($overlapResult->num_rows > 0) {
    // Overlap found, redirect back to referrer and display error modal
    $_SESSION['overlap_error'] = "The class schedule overlaps with another class.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}

$overlapStmt->close();

// Prepare the UPDATE statement
$sql = "
    UPDATE classes
    SET academic_year_id = ?, semester_id = ?, course_id = ?, year_section_id = ?, subject_id = ?, faculty_id = ?, time_start = ?, time_end = ?, day = ?, room_type = ?, building = ?, room_id = ?, isScheduled = ?
    WHERE id = ?
";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Preparation failed: " . $conn->error);
}

$isScheduled = 1; // Set isScheduled to true (1)
$stmt->bind_param("iiiiisssssssii", $academicYear, $semester, $course, $yearSection, $subject, $faculty, $startTime, $endTime, $day, $room_type, $building, $room, $isScheduled, $class_id);

// Execute the statement and check for errors
if ($stmt->execute()) {
    // Redirect back to referrer and display success modal
    $_SESSION['success'] = "Class successfully updated!";
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    // Redirect back to referrer and display error modal
    $_SESSION['error'] = "There was an error updating the class: " . $stmt->error;
    header("Location: " . $_SERVER['HTTP_REFERER']);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
