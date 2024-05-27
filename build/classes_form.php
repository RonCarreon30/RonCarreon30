<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">

<div class="mt-2 p-6 bg-white shadow-md rounded">
    <?php
    // Include the database configuration file
    include 'config.php';

    // Fetch courses
    $courses = $conn->query("SELECT id, course_name FROM courses");
    $academic_years = $conn->query("SELECT id, academic_year FROM academic_years");
    $semesters = $conn->query("SELECT id, semester_name, start_date FROM semesters");
    ?>
    <form method="post" action="save_classes.php">
        <div class="w-full px-3 mb-6">
            <label for="classAY" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Academic Year:</label>
            <select id="classAY" name="classAY" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                <option value="" disabled selected>Select Academic Year</option>
                <?php while ($row = $academic_years->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['academic_year']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="w-full px-3 mb-6">
            <label for="classSem" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Semester:</label>
            <select id="classSem" name="classSem" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                <option value="" disabled selected>Select Semester</option>
                <?php while ($row = $semesters->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['semester_name']. ' - ' . $row['start_date'];?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="w-full px-3 mb-6">
            <label for="classCourse" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Course:</label>
            <select id="classCourse" name="classCourse" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                <option value="" disabled selected>Select a course</option>
                <?php while ($row = $courses->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['course_name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="w-full px-3 mb-6">
            <label for="classYear_section" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Year & Section:</label>
            <select id="classYear_section" name="classYear_section" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                <option value="" disabled selected>Select year and section</option>
            </select>
        </div>

<div class="w-full px-3 mb-6">
    <label for="classSubject" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Subjects:</label>
    <select id="classSubject" name="classSubject" required class="subjects-select block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
        <option value="" disabled selected>Select subject</option>
    </select>
</div>

<div class="w-full px-3 mb-6">
    <label for="classroom_type" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Room Type:</label>
    <select id="classroom_type" name="classroom_type" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
        <option value="" disabled selected>Select room type</option>
        <option value="Classroom">Classroom</option>
        <option value="Laboratory">Laboratory</option>
        <option value="Mechanial">Mechanical</option>
        <!-- Add more options as needed -->
    </select>
</div>

               <!-- Start Time -->
        <div class="w-full px-3 mb-6">
            <label for="classStart_time" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Start Time:</label>
            <select id="classStart_time" name="classStart_time" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                <option value="" disabled selected>Select start time</option>
                <?php
                // Generate options for start time
                $start_time = strtotime('7:00 AM');
                $end_time = strtotime('8:00 PM');
                while ($start_time <= $end_time) {
                    echo '<option value="' . date('H:i', $start_time) . '">' . date('h:i A', $start_time) . '</option>';
                    $start_time += (30 * 60); // Increment by 30 minutes
                }
                ?>
            </select>
        </div>

        <!-- End Time -->
        <div class="w-full px-3 mb-6">
            <label for="classEnd_time" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">End Time:</label>
            <select id="classEnd_time" name="classEnd_time" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                <option value="" disabled selected>Select end time</option>
                <?php
                // Generate options for end time
                $start_time = strtotime('7:30 AM');
                $end_time = strtotime('8:30 PM');
                while ($start_time <= $end_time) {
                    echo '<option value="' . date('H:i', $start_time) . '">' . date('h:i A', $start_time) . '</option>';
                    $start_time += (30 * 60); // Increment by 30 minutes
                }
                ?>
            </select>
        </div>

        <div class="w-full px-3 mb-6">
            <label for="days" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Days:</label>
            <select id="days" name="days[]" multiple="multiple" required class="days-select block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
            </select>
        </div>

        <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
    </form>
</div>

<div class="mt-2 overflow-y-auto max-h-[calc(100vh-200px)]">
    <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">Something went wrong.</span>
    </div>

    <div class="bg-white py-2.5 shadow sm:rounded-lg sm:px-10">
        <h2 class="text-center text-lg font-semibold text-gray-900 mb-4">Classes List</h2>
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Course</th>
                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Year & Section</th>
                    <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                    <th class="px-8 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="classesList" class="bg-white divide-y divide-gray-200">
                <?php
                    // Database connection
                    include 'config.php';

                    // Query to fetch data from the classes table along with associated tables
                    $sql = "SELECT classes.id, academic_years.academic_year, courses.course_name, CONCAT(year_section.year_level, ' - ', year_section.section) AS year_section, subjects.subject_name 
                            FROM classes 
                            INNER JOIN academic_years ON classes.academic_year_id = academic_years.id 
                            INNER JOIN courses ON classes.course_id = courses.id 
                            INNER JOIN year_section ON classes.year_section_id = year_section.id 
                            INNER JOIN subjects ON classes.subject_id = subjects.id";
                    $result = $conn->query($sql);

                    // Check if there are any records
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["academic_year"] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["course_name"] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["year_section"] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["subject_name"] . "</td>";
                            echo "<td class='px-8 py-4 whitespace-nowrap'>
                                    <div class='flex items-center'>
                                        <button type='button' class='inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2' onclick='editYearSection(" . $row["id"] . ")'>
                                            Edit
                                        </button>
                                        <button type='button' class='ml-2 inline-flex justify-center rounded-md border border-red-500 shadow-sm px-4 py-2 bg-red-500 text-sm font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2' onclick='deleteYearSection(" . $row["id"] . ")'>
                                            Delete
                                        </button>
                                    </div>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        // Output a message if no data found
                        echo "<tr><td colspan='5'>No data found</td></tr>";
                    }

                    // Close the database connection
                    $conn->close();
                ?>


            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {

        $('.days-select').select2({
            placeholder: "Select days (Max 2)",
            maximumSelectionLength: 2,
            allowClear: true
        });

        $('#classCourse').on('change', function() {
            var courseId = $(this).val();

            if (courseId) {
                // Fetch year sections
                $.ajax({
                    url: 'fetch_year_sections.php',
                    type: 'POST',
                    data: {course_id: courseId},
                    success: function(data) {
                        $('#classYear_section').html(data);
                    },
                    error: function() {
                        $('#error-message').removeClass('hidden');
                    }
                });

                // Fetch subjects
                $.ajax({
                    url: 'fetch_course_subjects.php',
                    type: 'POST',
                    data: {course_id: courseId},
                    success: function(data) {
                        $('#classSubject').html(data);
                    },
                    error: function() {
                        $('#error-message').removeClass('hidden');
                    }
                });
            } else {
                $('#classYear_section').html('<option value="" disabled selected>Select year and section</option>');
                $('#classSubject').html('<option value="" disabled selected>Select subjects</option>');
            }
        });
    });
</script>
