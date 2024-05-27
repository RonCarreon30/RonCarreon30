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
if ($_SESSION['role'] !== 'Dept. Head') {
    // Redirect to a page indicating unauthorized access
    header("Location: index.html");
    exit();
}

// Include the database configuration file
include 'config.php';

// Perform a JOIN query to fetch the data from the classes table and related tables
$sql = "SELECT 
            c.id AS class_id,
            ay.academic_year,
            s.semester_name,
            cr.course_name,
            ys.year_level,
            ys.section,
            sj.subject_name,
            f.faculty_name,
            c.time_start,
            c.time_end,
            c.day,
            c.room_type,
            r.building,
            r.room_number
        FROM 
            classes c
        INNER JOIN academic_years ay ON c.academic_year_id = ay.id
        INNER JOIN semesters s ON c.semester_id = s.id
        INNER JOIN courses cr ON c.course_id = cr.id
        INNER JOIN year_section ys ON c.year_section_id = ys.id
        INNER JOIN subjects sj ON c.subject_id = sj.id
        INNER JOIN faculty f ON c.faculty_id = f.id
        INNER JOIN rooms r ON c.room_id = r.room_id";

$result = $conn->query($sql);

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

        <!-- Load the Sidebar here -->
        <div id="sidebar-container">
            <?php include 'sidebar.php'; ?>
        </div>

        <!-- Content area -->
        <div class="flex flex-col flex-1">
            <header class="bg-white shadow-lg">
                <div class="flex items-center justify-between px-6 py-3 border-b">
                    <h2 class="text-lg font-semibold">Room Loading</h2>
                </div>
            </header>

            <!-- Main content area -->
            <main class="flex-1 p-4 overflow-y-auto">
                <div class="flex h-full flex-row items-center space-x-4">
                    <div class="flex h-full w-8/12 flex-col space-y-2">
                        <div class="flex flex-col space-y-2 p-2">
                            <!-- Form for room load details -->
                                <?php
                                // Include the database configuration file
                                include 'config.php';

                                // Fetch courses
                                $classes = $conn->query("SELECT id, class_name FROM classes");
                                $buildings = $conn->query("SELECT DISTINCT building FROM rooms");
                                ?>
                            <form id="classScheduleForm" action="save_room_load.php" method="POST" class="w-full max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Academic Year dropdown -->
                                    <div class="mb-4">
                                        <label for="academicYear" class="block text-gray-700 font-bold mb-2">Academic Year:</label>
                                        <select id="academicYear" name="academicYear" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                                            <!-- Options will be dynamically populated -->
                                        </select>
                                    </div>
                                    <!-- Semester dropdown -->
                                    <div class="mb-4">
                                        <label for="semester" class="block text-gray-700 font-bold mb-2">Semester:</label>
                                        <select id="semester" name="semester" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                                            <!-- Options will be dynamically populated -->
                                        </select>
                                    </div>
                                    <!-- Course dropdown -->
                                    <div class="mb-4">
                                        <label for="class" class="block text-gray-700 font-bold mb-2">Class:</label>
                                        <select id="class" name="class" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                                            <!-- Options will be dynamically populated -->
                                            <?php while ($row = $classes->fetch_assoc()): ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['class_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <!-- Year and Section dropdown -->
                                    <div class="mb-4">
                                        <label for="course" class="block text-gray-700 font-bold mb-2">Course:</label>
                                        <select id="course" name="course" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                                            <!-- Options will be dynamically populated -->
                                        </select>
                                    </div>
                                    <!-- Year and Section dropdown -->
                                    <div class="mb-4">
                                        <label for="yearSection" class="block text-gray-700 font-bold mb-2">Year & Section:</label>
                                        <select id="yearSection" name="yearSection" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                                            <!-- Options will be dynamically populated -->
                                        </select>
                                    </div>
                                    <!-- Subject dropdown -->
                                    <div class="mb-4">
                                        <label for="subject" class="block text-gray-700 font-bold mb-2">Subject:</label>
                                        <select id="subject" name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                                            <!-- Options will be dynamically populated -->
                                        </select>
                                    </div>
                                    <!-- Faculty dropdown -->
                                    <div class="mb-4">
                                        <label for="faculty" class="block text-gray-700 font-bold mb-2">Faculty:</label>
                                        <select id="faculty" name="faculty" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                                            <!-- Options will be dynamically populated -->
                                        </select>
                                    </div>
                                    <!-- Time Start dropdown -->
                                    <div class="mb-4">
                                        <label for="startTime" class="block text-gray-700 font-bold mb-2">Time Start:</label>
                                        <select id="startTime" name="startTime" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                                            <!-- Options will be dynamically populated -->
                                        </select>
                                    </div>
                                    <!-- Time End dropdown -->
                                    <div class="mb-4">
                                        <label for="endTime" class="block text-gray-700 font-bold mb-2">Time End:</label>
                                        <select id="endTime" name="endTime" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                                            <!-- Options will be dynamically populated -->
                                        </select>
                                    </div>
                                    <!-- Day dropdown -->
                                    <div class="mb-4">
                                        <label for="day" class="block text-gray-700 font-bold mb-2">Day:</label>
                                        <select id="day" name="day" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                                            <!-- Options will be dynamically populated -->
                                        </select>
                                    </div>
                                    <!-- Room Type dropdown -->
                                    <div class="mb-4">
                                        <label for="room_type" class="block text-gray-700 font-bold mb-2">Room Type:</label>
                                        <select id="room_type" name="room_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                                            <!-- Options will be dynamically populated -->
                                        </select>
                                    </div>
                                    <!-- Building dropdown -->
                                    <div class="mb-4">
                                        <label for="building" class="block text-gray-700 font-bold mb-2">Building:</label>
                                        <select id="building" name="building" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                                            <option value="" disabled selected>Select Building</option>
                                            <?php while ($row = $buildings->fetch_assoc()): ?>
                                                <option value="<?php echo $row['building']; ?>"><?php echo $row['building']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <!-- Room dropdown -->
                                    <div class="mb-4">
                                        <label for="room" class="block text-gray-700 font-bold mb-2">Room:</label>
                                        <select id="room" name="room" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400">
                                            <option value="" disabled selected>Select Room</option>
                                            <!-- Options will be dynamically populated based on the selected building -->
                                        </select>
                                    </div>
                                </div>
                                <!-- Submit button -->
                                <div class="flex justify-center mt-5">
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="h-full border-l border-gray-300"></div>
                    <div class="flex flex-col h-full w-1/3 space-y-4">
                        <div>
                            <h1 class="text-lg font-bold">Room Loads</h1>
                        </div>
                        <!-- Display room loads data in cards -->
                        <?php
                        // Check if any data is found
                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='bg-white p-4 rounded-lg shadow-lg mb-4'>";
                                echo "<h2 class='text-lg font-semibold'>Class ID: " . $row["class_id"] . "</h2>";
                                echo "<p>Academic Year: " . $row["academic_year"] . "</p>";
                                echo "<p>Semester: " . $row["semester_name"] . "</p>";
                                echo "<p>Course: " . $row["course_name"] . "</p>";
                                echo "<p>Year Level: " . $row["year_level"] . "</p>";
                                echo "<p>Section: " . $row["section"] . "</p>";
                                echo "<p>Subject: " . $row["subject_name"] . "</p>";
                                echo "<p>Faculty: " . $row["faculty_name"] . "</p>";
                                echo "<p>Time Start: " . $row["time_start"] . "</p>";
                                echo "<p>Time End: " . $row["time_end"] . "</p>";
                                echo "<p>Day: " . $row["day"] . "</p>";
                                echo "<p>Room Type: " . $row["room_type"] . "</p>";
                                echo "<p>Building: " . $row["building"] . "</p>";
                                echo "<p>Room Number: " . $row["room_number"] . "</p>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>No classes found</p>";
                        }

                        // Close the database connection
                        $conn->close();
                        ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- HTML for custom confirmation dialog -->
    <div id="custom-dialog" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md flex flex-col items-center">
            <img class="w-36 mb-4" src="img/undraw_warning_re_eoyh.svg" alt="">
            <p class="text-lg text-slate-700 font-semibold mb-4">Are you sure you want to logout?</p>
            <div class="flex justify-center mt-5">
                <button onclick="cancelLogout()" class="mr-4 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Cancel</button>
                <button onclick="confirmLogout()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-500">Logout</button>
            </div>
        </div>
    </div>

    <script src="scripts/logout.js"></script>
    <script src="scripts/functions.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#class').on('change', function() {
                var classId = $(this).val();

                if (classId) {
                    // Make an AJAX request to fetch data based on the selected class
                    $.ajax({
                        url: 'fetch_class_data.php', // Replace with the actual URL to your PHP script
                        type: 'POST',
                        data: {class_id: classId},
                        success: function(data) {
                            // Parse the returned JSON data and populate the dropdowns
                            var parsedData = JSON.parse(data);
                            $('#academicYear').html(parsedData.academicYears);
                            $('#semester').html(parsedData.semesters);
                            $('#course').html(parsedData.course);
                            $('#yearSection').html(parsedData.yearSections);
                            $('#subject').html(parsedData.subjects);
                            $('#faculty').html(parsedData.faculty);
                            $('#room_type').html(parsedData.room_type);
                        },
                        error: function() {
                            // Handle errors if any
                            console.log('Error fetching data');
                        }
                    });
                } else {
                    // Clear the dropdowns if no class is selected
                    $('#academicYear').html('<option value="" disabled selected>Select Academic Year</option>');
                    $('#semester').html('<option value="" disabled selected>Select Semester</option>');
                    $('#course').html('<option value="" disabled selected>Select course</option>');
                    $('#yearSection').html('<option value="" disabled selected>Select Year & Section</option>');
                    $('#subject').html('<option value="" disabled selected>Select Subject</option>');
                    $('#faculty').html('<option value="" disabled selected>Select Faculty</option>');
                    $('#room_type').html('<option value="" disabled selected>Select Room Type</option>');
                }
            });

            $('#building').on('change', function() {
                var building = $(this).val();

                if (building) {
                    // Make an AJAX request to fetch rooms based on the selected building
                    $.ajax({
                        url: 'fetch_rooms.php',
                        type: 'POST',
                        data: {building: building},
                        success: function(data) {
                            // Parse the returned JSON data and populate the rooms dropdown
                            var parsedData = JSON.parse(data);
                            $('#room').html(parsedData.rooms);
                        },
                        error: function() {
                            // Handle errors if any
                            console.log('Error fetching rooms');
                        }
                    });
                } else {
                    // Clear the rooms dropdown if no building is selected
                    $('#room').html('<option value="" disabled selected>Select Room</option>');
                }
            });
        });
            // Function to generate time options from 7:00 AM to 8:00 PM with 30-minute intervals
    function generateTimeOptions() {
        var selectStartTime = document.getElementById("startTime");
        var selectEndTime = document.getElementById("endTime");

        for (var hour = 7; hour <= 20; hour++) {
            for (var min = 0; min < 60; min += 30) {
                var period = (hour >= 12) ? "PM" : "AM";
                var displayHour = (hour > 12) ? hour - 12 : hour;
                if (displayHour === 0) displayHour = 12;
                var time = displayHour + ":" + (min === 0 ? "00" : min) + " " + period;
                var option = document.createElement("option");
                option.text = time;
                option.value = time;
                selectStartTime.add(option.cloneNode(true));
                selectEndTime.add(option.cloneNode(true));
            }
        }
    }

    // Function to generate options for days from Monday to Saturday
    function generateDayOptions() {
        var selectDay = document.getElementById("day");
        var days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

        days.forEach(function(day) {
            var option = document.createElement("option");
            option.text = day;
            option.value = day;
            selectDay.add(option);
        });
    }

    $(document).ready(function() {
        // Call functions to generate time and day options
        generateTimeOptions();
        generateDayOptions();

        // Other JavaScript code...
    });
    </script>
</body>
</html>