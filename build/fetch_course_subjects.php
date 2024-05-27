<?php
include 'config.php';

if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    // Fetch subjects based on the selected course
    $result = $conn->query("SELECT s.id, s.subject_name 
                            FROM subjects s
                            INNER JOIN course_subjects cs ON s.id = cs.subject_id
                            WHERE cs.course_id = $course_id");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="'.$row['id'].'">'.$row['subject_name'].'</option>';
        }
    } else {
        echo '<option value="" disabled>No subjects available</option>';
    }
}
?>
