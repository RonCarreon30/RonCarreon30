<?php
include 'config.php';

if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    // Fetch year sections based on the selected course
    $result = $conn->query("SELECT ys.id, CONCAT(ys.year_level, '-', ys.section) AS year_section
                            FROM year_section ys
                            JOIN course_year_section cys ON ys.id = cys.year_section_id
                            WHERE cys.course_id = $course_id");

    if ($result->num_rows > 0) {
        echo '<option value="" disabled selected>Select year and section</option>';
        while ($row = $result->fetch_assoc()) {
            echo '<option value="'.$row['id'].'">'.$row['year_section'].'</option>';
        }
    } else {
        echo '<option value="" disabled>No year and section available</option>';
    }
}
?>
