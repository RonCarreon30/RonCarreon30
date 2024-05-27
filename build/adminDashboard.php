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
if ($_SESSION['role'] !== 'Admin') {
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

// Fetch the user data from the database
$user_query = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_query);
$user_data = $user_result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLV: RESERVA</title>
    <link rel="stylesheet" href="css/style.css">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css" />
</head>
<body>
    <div class="flex h-screen bg-gray-100">
        
        <!-- Load the Sidebar here   -->
        <div id="sidebar-container">
            <?php include 'sidebar.php'; ?>
        </div>

        <div class="flex flex-col flex-1">
            <header class="bg-white shadow-lg">
                <div class="flex items-center justify-between px-6 py-3 border-b">
                    <h2 class="text-lg font-semibold">
                        Admin's Dashboard
                    </h2>
                </div>
            </header>
            <!-- Main content area -->
            <main class="flex flex-1 p-4 h-screen overflow-y-auto">
                <div class="w-3/4 pr-4">
                    <div class="mb-4">
                        <!--Banner-->
                        <div class="relative bg-blue-300 text-white p-6 m-2 rounded-md lg:h-32 xl:h-40 md:h-24 sm:h-20 flex justify-between max-h-40 overflow-hidden">
                            <div class="w-full md:w-3/4">
                                <h2 class="text-lg lg:text-xl xl:text-2xl font-semibold pb-1">Welcome, <?php echo $user_data['first_name'] . ' ' . $user_data['last_name']; ?></h2>
                                <p class="text-sm lg:text-base">Welcome to your dashboard! From here, you can efficiently manage room loadings, view schedules, and input essential data for classes and other academic-related management. If you require assistance, please don't hesitate to reach out to our support team.</p>
                            </div>
                            <div class="hidden md:block w-1/4">
                                <img class="h-auto w-full" src="img/undraw_hello_re_3evm.svg" alt="Greeting SVG">
                            </div>
                        </div>
                    </div>
                    <!-- Widgets -->
                    <div class="grid grid-cols-2 m-2 gap-4">
                        <!-- Total Users -->
                        <div class="flex items-center rounded bg-white p-6 shadow-md h-40">
                            <i class="fas fa-users fa-3x w-1/4 text-blue-600"></i>
                            <div class="ml-4 w-3/4">
                                <h2 class="text-lg font-bold">Total Users</h2>
                                <p class="text-2xl">100</p>
                            </div>
                        </div>

                        <!-- Total Rooms -->
                        <div class="flex items-center rounded bg-white p-6 shadow-md h-40">
                            <i class="fas fa-door-closed fa-3x w-1/4 text-blue-600"></i>
                            <div class="ml-4 w-3/4">
                                <h2 class="text-lg font-bold">Total Rooms</h2>
                                <p class="text-2xl">50</p>
                            </div>
                        </div>

                        <!-- Total Facilities -->
                        <div class="flex items-center rounded bg-white p-6 shadow-md h-40">
                            <i class="fas fa-building fa-3x w-1/4 text-blue-600"></i>
                            <div class="ml-4 w-3/4">
                                <h2 class="text-lg font-bold">Total Facilities</h2>
                                <p class="text-2xl">20</p>
                            </div>
                        </div>

                        <!-- New Password Requests -->
                        <div class="flex items-center rounded bg-white p-6 shadow-md h-40">
                            <i class="fas fa-key fa-3x w-1/4 text-blue-600"></i>
                            <div class="ml-4 w-3/4">
                                <h2 class="text-lg font-bold">New Password Requests</h2>
                                <p class="text-2xl">5</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="h-full border-l border-gray-300"></div>
                <!-- Calendar -->
                <div class="flex flex-col h-full w-1/3 space-y-4">
                    <div id="calendar-container" class="h-1/2">
                        <div id="calendar" class="p-1 text-xs bg-white border border-gray-200 rounded-lg shadow-lg h-full"></div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth'
            });
            calendar.render();
        });
    </script>
</body>
</html>
