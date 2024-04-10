<?php
// Include your database connection file
include_once 'config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $days = explode(',', $_POST['day']); // Convert string to array
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
    $academicYear = $_POST['academicYear'];
    $semester = $_POST['semester'];
    $courseYear = $_POST['courseYear'];
    $course = $_POST['course'];
    $section = $_POST['section'];
    $subject = $_POST['subject'];
    $teacher = $_POST['teacher'];
    $room = $_POST['room'];

    // Prepare and execute SQL statement to insert data into the classschedule table
    $sql = "INSERT INTO classschedule (monday, tuesday, wednesday, thursday, friday, saturday, startTime, endTime, academicYear, semester, yearLevel, course, section, subject, teacher, room) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, 'iiiiiiisssssssss', $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $startTime, $endTime, $academicYear, $semester, $courseYear, $course, $section, $subject, $teacher, $room);

    // Set values for day columns based on selected days
    $monday = in_array('monday', $days) ? 1 : 0;
    $tuesday = in_array('tuesday', $days) ? 1 : 0;
    $wednesday = in_array('wednesday', $days) ? 1 : 0;
    $thursday = in_array('thursday', $days) ? 1 : 0;
    $friday = in_array('friday', $days) ? 1 : 0;
    $saturday = in_array('saturday', $days) ? 1 : 0;

    // Execute statement
    if (mysqli_stmt_execute($stmt)) {
        // Data inserted successfully
        echo "Class schedule added successfully!";
    } else {
        // Error handling
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close database connection
    mysqli_close($conn);
}
?>
