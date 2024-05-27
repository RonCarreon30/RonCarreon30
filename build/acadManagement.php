<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header("Location: index.html");
    exit();
}

// Check if the user has the required role
if ($_SESSION['role'] !== 'Registrar' && $_SESSION['role'] !== 'Admin') {
    // Redirect to a page indicating unauthorized access
    header("Location: index.html");
    exit();
}

// Fetch reservations from the database for the current user
$servername = "localhost";
$username = "root";
$db_password = ""; // Change this if you have set a password for your database
$dbname = "reservadb";

// Create connection
$conn = new mysqli($servername, $username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the user ID from the session data
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLV: RESERVA</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="flex h-screen bg-gray-100">
        
        <!-- Load the Sidebar here   -->
        <div id="sidebar-container">
            <?php include 'sidebar.php'; ?>
        </div>
    
        <!-- Content area -->
        <div class="flex flex-col flex-1">
            <header class="bg-white shadow-lg">
                <div class="flex items-center justify-between px-6 py-3 border-b ">
                    <h2 class="text-lg font-semibold">
                        Academic Administration
                    </h2>
                </div>
            </header>

            <main class="flex-1 p-4 h-screen overflow-y-auto">
                <div class="bg-blue-300 shadow-md rounded p-1">
                    <ul class="flex flex-wrap -mb-px" id="formTabs">
                        <li class="mr-2">
                            <a class="text-sm py-1 px-2 block whitespace-nowrap hover:border-blue-500 hover:text-blue-600" href="#academic-year-form">
                                <span class="font-semibold">Academic Year</span>
                            </a>
                        </li>
                        <li class="mr-2">
                            <a class="text-sm py-1 px-2 block whitespace-nowrap hover:border-blue-500 hover:text-blue-600" href="#semester-form">
                                <span class="font-semibold">Semester</span>
                            </a>
                        </li>
                        <li class="mr-2">
                            <a class="text-sm py-1 px-2 block whitespace-nowrap hover:border-blue-500 hover:text-blue-600" href="#faculty-form">
                                <span class="font-semibold">Faculty</span>
                            </a>
                        </li>
                        <li class="mr-2" style="border-bottom: 1px solid #ccc; padding-bottom: 10px;">
                        </li>
                        <li class="mr-2">
                            <a class="text-sm py-1 px-2 block whitespace-nowrap hover:border-blue-500 hover:text-blue-600" href="#subject-form">
                                <span class="font-semibold">Subject</span>
                            </a>
                        </li>
                        <li class="mr-2">
                            <a class="text-sm py-1 px-2 block whitespace-nowrap hover:border-blue-500 hover:text-blue-600" href="#course-form">
                                <span class="font-semibold">Course</span>
                            </a>
                        </li>
                        <li class="mr-2">
                            <a class="text-sm py-1 px-2 block whitespace-nowrap hover:border-blue-500 hover:text-blue-600" href="#year-section-form">
                                <span class="font-semibold">Year Level & Section</span>
                            </a>
                        </li>
                        <!-- Add a new list item for the Assign Courses, Year Levels, and Sections tab -->
                        <li class="mr-2">
                            <a class="text-sm py-1 px-2 block whitespace-nowrap hover:border-blue-500 hover:text-blue-600" href="#assign-course-yr-section">
                                <span class="font-semibold">Assign Course Year Level & Section</span>
                            </a>
                        </li>
                        <li class="mr-2">
                            <a class="text-sm py-1 px-2 block whitespace-nowrap hover:border-blue-500 hover:text-blue-600" href="#assign-subject-form">
                                <span class="font-semibold">Assign Subjects</span>
                            </a>
                        </li>
                        <!-- Add a new list item for the Classes tab -->
                        <li class="mr-2">
                            <a class="text-sm py-1 px-2 block whitespace-nowrap hover:border-blue-500 hover:text-blue-600" href="#classes-form">
                                <span class="font-semibold">Classes</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="w-full">
                    <!-- Academic Year Form -->
                    <div id="academic-year-form" class="">
                        <?php include 'AY_form.php'?>
                    </div>

                    <!-- Semester Form -->
                    <div id="semester-form" class="hidden">
                        <?php include 'sem_form.php' ?>
                    </div>

                    <!-- Faculty Form -->
                    <div id="faculty-form" class="hidden">
                        <?php include 'faculty_form.php' ?>
                    </div>

                    <!-- Subject Form -->
                    <div id="subject-form" class="hidden">
                        <?php include 'subject_form.php' ?>
                    </div>

                    <!-- Course Form -->
                    <div id="course-form" class="hidden">
                        <?php include 'course_form.php'; ?> 
                    </div>

                    <!-- Year Level Form -->
                    <div id="year-section-form" class="hidden">
                        <?php include 'year_section_form.php' ?>
                    </div>

                    <!-- Add a new division for the Assign Courses, Year Levels, and Sections form -->
                    <div id="assign-course-yr-section" class="hidden">
                        <?php include 'assign_course_yr.php'; ?>
                    </div>

                    <!-- Subject to COurse Form -->
                    <div id="assign-subject-form" class="hidden">
                        <?php include 'assign_subjects.php'; ?>
                    </div>
                    <!-- Add a new division for the Classes form -->
                    <div id="classes-form" class="hidden">
                        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                                <div class="flex flex-wrap -mx-3 mb-6">
                                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                        <label for="classAY" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Academic Year:</label>
                                        <select id="classAY" name="classAY" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                                            <option value="" disabled selected>Select Academic Year</option>
                                            <?php while ($row = $academic_years->fetch_assoc()): ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['academic_year']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="w-full md:w-1/2 px-3">
                                        <label for="classSem" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Semester:</label>
                                        <select id="classSem" name="classSem" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                                            <option value="" disabled selected>Select Semester</option>
                                            <?php while ($row = $semesters->fetch_assoc()): ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['semester_name'] . ' - ' . $row['start_date']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex flex-wrap -mx-3 mb-6">
                                    <div class="w-full md:w-1/2 px-3">
                                        <label for="classCourse" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Course:</label>
                                        <select id="classCourse" name="classCourse" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                                            <option value="" disabled selected>Select a course</option>
                                            <?php while ($row = $courses->fetch_assoc()): ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['course_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="w-full md:w-1/2 px-3">
                                        <label for="classYear_section" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Year & Section:</label>
                                        <select id="classYear_section" name="classYear_section" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                                            <option value="" disabled selected>Select year and section</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex flex-wrap -mx-3 mb-6">
                                    <div class="w-full md:w-1/2 px-3">
                                        <label for="classSubject" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Subjects:</label>
                                        <select id="classSubject" name="classSubject" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                                            <option value="" disabled selected>Select subject</option>
                                        </select>
                                    </div>
                                    <div class="w-full md:w-1/2 px-3">
                                        <label for="room_type" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Room Type:</label>
                                        <select id="room_type" name="room_type" required class="block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white">
                                            <option value="" disabled selected>Select room type</option>
                                            <option value="Classroom">Classroom</option>
                                            <option value="Laboratory">Laboratory</option>
                                            <option value="Mechanial">Mechanical</option>
                                            <!-- Add more options as needed -->
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
                            </form>
                        </div>
                        <!--Year Level & Section List -->
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
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- Logout confirmation modal -->
    <div id="custom-dialog" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md flex flex-col items-center">
            <img class="w-36 mb-4" src="img\undraw_warning_re_eoyh.svg" alt="">
            <p class="text-lg text-slate-700 font-semibold mb-4">Are you sure you want to logout?</p>
            <div class="flex justify-center mt-5">
                <button onclick="cancelLogout()" class="mr-4 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Cancel</button>
                <button onclick="confirmLogout()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-500">Logout</button>
            </div>
        </div>
    </div> 
<script src="scripts/logout.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Tabs functionality
    $('#formTabs a').on('click', function(e) {
        e.preventDefault();
        var tab = $(this).attr('href');
        $('#formTabs a').removeClass('border-blue-500 text-blue-600');
        $(this).addClass('border-blue-500 text-blue-600');
        // Hide all form sections except the one corresponding to the clicked tab
        $('#academic-year-form, #semester-form, #faculty-form, #subject-form, #course-form, #year-section-form, #assign-subject-form, #assign-course-yr-section, #classes-form').addClass('hidden');
        $(tab).removeClass('hidden');
    });

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

</body>
</html>