<?php
// Include the database configuration file
include 'config.php';

// Check if class_id is set and not empty
if (isset($_POST['class_id']) && !empty($_POST['class_id'])) {
    // Sanitize the input to prevent SQL injection
    $classId = $conn->real_escape_string($_POST['class_id']);

    // Prepare the data array to store dropdown options
    $data = array();

    // Fetch data from the database based on the selected class ID

    // 1. Fetch Academic Years
    $academicYearQuery = "SELECT id, academic_year FROM academic_years WHERE id IN (SELECT academic_year_id FROM classes WHERE id = $classId)";
    $academicYearResult = $conn->query($academicYearQuery);
    $academicYears = '<option value="" disabled selected>Select Academic Year</option>';
    while ($row = $academicYearResult->fetch_assoc()) {
        $academicYears .= '<option value="' . $row['id'] . '">' . $row['academic_year'] . '</option>';
    }
    $data['academicYears'] = $academicYears;

    // 2. Fetch Semesters (Assuming you have a table named semesters)
    $semesterQuery = "SELECT id, semester_name FROM semesters WHERE id IN (SELECT semester_id FROM classes WHERE id = $classId)";
    $semesterResult = $conn->query($semesterQuery);
    $semesters = '<option value="" disabled selected>Select Semester</option>';
    while ($row = $semesterResult->fetch_assoc()) {
        $semesters .= '<option value="' . $row['id'] . '">' . $row['semester_name'] . '</option>';
    }
    $data['semesters'] = $semesters;

    // 3. Fetch Year & Section
    $yearSectionQuery = "SELECT id, year_level, section FROM year_section WHERE id IN (SELECT year_section_id FROM classes WHERE id = $classId)";
    $yearSectionResult = $conn->query($yearSectionQuery);
    $yearSection = '<option value="" disabled selected>Select Year Level</option>';
    while ($row = $yearSectionResult->fetch_assoc()) {
        $yearSection .= '<option value="' . $row['id'] . '">' . $row['year_level'] . ' - ' . $row['section'] . '</option>';
    }
    $data['yearSections'] = $yearSection;


    // 4. Fetch Subjects
    $subjectQuery = "SELECT id, subject_name FROM subjects WHERE id IN (SELECT subject_id FROM classes WHERE id = $classId)";
    $subjectResult = $conn->query($subjectQuery);
    $subjects = '<option value="" disabled selected>Select Subject</option>';
    while ($row = $subjectResult->fetch_assoc()) {
        $subjects .= '<option value="' . $row['id'] . '">' . $row['subject_name'] . '</option>';
    }
    $data['subjects'] = $subjects;

// Fetch Faculty
$facultyQuery = "SELECT id, faculty_name FROM faculty WHERE department = (SELECT class_college FROM classes WHERE id = $classId)";
$facultyResult = $conn->query($facultyQuery);
$faculty = '<option value="" disabled selected>Select Faculty</option>';
while ($row = $facultyResult->fetch_assoc()) {
    $faculty .= '<option value="' . $row['id'] . '">' . $row['faculty_name'] . '</option>';
}
$data['faculty'] = $faculty;

// Fetch Room Type
$roomTypeQuery = "SELECT room_type FROM classes WHERE id = $classId";
$roomTypeResult = $conn->query($roomTypeQuery);
$roomTypeOptions = '<option value="" disabled selected>Select Room Type</option>';
while ($row = $roomTypeResult->fetch_assoc()) {
    $roomTypeOptions .= '<option value="' . $row['room_type'] . '">' . $row['room_type'] . '</option>';
}
$data['room_type'] = $roomTypeOptions;

    // 4. Fetch course
$courseQuery = "SELECT id, course_code FROM courses WHERE id IN (SELECT course_id FROM classes WHERE id = $classId)";
$courseResult = $conn->query($courseQuery);

$courses = '<option value="" disabled selected>Select Course</option>';
while ($row = $courseResult->fetch_assoc()) {
    $courses .= '<option value="' . $row['id'] . '">' . $row['course_code'] . '</option>';
}

$data['course'] = $courses;

    // Similarly, fetch data for other dropdowns and append them to the $data array

    // Encode the data as JSON and send it back
    echo json_encode($data);
} else {
    // Handle the case where class_id is not set or empty
    echo json_encode(['error' => 'Class ID is missing']);
}

// Close the database connection
$conn->close();
?>
