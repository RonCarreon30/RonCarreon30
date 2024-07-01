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
if ($_SESSION['role'] !== 'Facility Head') {
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

// Fetch the total number of facilities
$count_query = "SELECT COUNT(*) as total FROM facilities"; // Adjust the table name as per your database
$count_result = $conn->query($count_query);

$total_facilities = 0;
if ($count_result->num_rows > 0) {
    $row = $count_result->fetch_assoc();
    $total_facilities = $row['total'];
}

$reservations = [];
$reservation_sql = "SELECT * FROM reservations WHERE reservation_status = 'Reserved'";
$reservation_result = $conn->query($reservation_sql);

    // Query to fetch reservations for the current user and order them by reservation date in descending order
    $all_reservation_sql = "SELECT * FROM reservations ORDER BY created_at DESC";
    $all_reservation_result = $conn->query($all_reservation_sql);

if ($reservation_result->num_rows > 0) {
    while ($row = $reservation_result->fetch_assoc()) {
        $reservation = [
            'title' => $row['facility_name'],
            'start' => $row['reservation_date'] . 'T' . $row['start_time'],
            'end' => $row['reservation_date'] . 'T' . $row['end_time'],
            'backgroundColor' => '#f00', // Color for reserved events
            'userDepartment' => $row['user_department'],
            // Add more details as needed
        ];
        $reservations[] = $reservation;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLV: RESERVA</title>
    <link rel="stylesheet" href="css/style.css">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script>
                document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?php echo json_encode($reservations); ?>,
                eventContent: function(info) {
                    console.log(info.event);
                    return info.event.title;
                }
            });
            calendar.render();
        });
    </script>
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
                <div class="flex items-center justify-between px-6 py-3 border-b">
                    <h2 class="text-lg font-semibold">Facility Head Dashboard</h2>
                </div>
            </header>

            <!-- Main Content goes here-->
            <main class="flex flex-1 p-4 h-screen overflow-y-auto">
                <!-- Banner -->
                <div class="w-3/4">
                    <div class="mb-4">
                        <!-- Banner -->
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
                    
                    <!--Widgets-->
                    <div class="grid grid-cols-2 m-2 gap-4">
                        <div class="flex items-center rounded bg-white p-6 shadow-md h-40">
                            <i class="fas fa-building fa-3x w-1/4 text-blue-600"></i>
                            <div class="ml-4 w-3/4">
                                <h2 class="text-lg font-bold">Total Number Of Facilities</h2>
                                <?php
                                // Fetch count of total facilities
                                $facility_count_sql = "SELECT COUNT(*) AS count FROM facilities";
                                $facility_count_result = $conn->query($facility_count_sql);

                                if ($facility_count_result) {
                                    $row = $facility_count_result->fetch_assoc();
                                    $facility_count = $row['count'];
                                    echo '<p class="text-2xl">' . $facility_count . '</p>';
                                } else {
                                    echo '<p class="text-2xl">0</p>';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="flex items-center rounded bg-white p-6 shadow-md h-40">
                            <i class="fas fa-calendar-check fa-3x w-1/4 text-green-600"></i>
                            <div class="ml-4 w-3/4">
                                <h2 class="text-lg font-bold">Facilities with Reservation</h2>
                                <?php
                                // Fetch count of distinct facilities that have a "Reserved" reservation status
                                $facilities_with_reservation_sql = "
                                    SELECT COUNT(DISTINCT facility_id) AS count 
                                    FROM reservations 
                                    WHERE reservation_status = 'Reserved'
                                ";
                                $facilities_with_reservation_result = $conn->query($facilities_with_reservation_sql);

                                if ($facilities_with_reservation_result) {
                                    $row = $facilities_with_reservation_result->fetch_assoc();
                                    $facilities_with_reservation_count = $row['count'];
                                    echo '<p class="text-2xl">' . $facilities_with_reservation_count . '</p>';
                                } else {
                                    echo '<p class="text-2xl">0</p>';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="flex items-center rounded bg-white p-6 shadow-md h-40">
                            <i class="fas fa-list fa-3x w-1/4 text-orange-600"></i>
                            <div class="ml-4 w-3/4">
                                <h2 class="text-lg font-bold">Total Number of Reservations</h2>
                                <?php
                                // Fetch count of total reservations
                                $total_reservations_sql = "SELECT COUNT(*) AS count FROM reservations";
                                $total_reservations_result = $conn->query($total_reservations_sql);

                                if ($total_reservations_result) {
                                    $row = $total_reservations_result->fetch_assoc();
                                    $total_reservations_count = $row['count'];
                                    echo '<p class="text-2xl">' . $total_reservations_count . '</p>';
                                } else {
                                    echo '<p class="text-2xl">0</p>';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="flex items-center rounded bg-white p-6 shadow-md h-40">
                            <i class="fas fa-calendar-times fa-3x w-1/4 text-red-600"></i>
                            <div class="ml-4 w-3/4">
                                <h2 class="text-lg font-bold">Facilities with No Reservations</h2>
                                <?php
                                // Fetch count of facilities with no reservations
                                $no_reservations_sql = "
                                    SELECT COUNT(*) AS count 
                                    FROM facilities f 
                                    LEFT JOIN reservations r ON f.facility_name = r.facility_name 
                                    WHERE r.facility_name IS NULL";
                                $no_reservations_result = $conn->query($no_reservations_sql);

                                if ($no_reservations_result) {
                                    $row = $no_reservations_result->fetch_assoc();
                                    $no_reservations_count = $row['count'];
                                    echo '<p class="text-2xl">' . $no_reservations_count . '</p>';
                                } else {
                                    echo '<p class="text-2xl">0</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="h-full border-l border-gray-300"></div>

                <div class="flex flex-col h-full w-1/3 space-y-4">
                    <div class="h-1/2 p-2">
                        <div id='calendar' class="h-full p-1 text-xs bg-white border border-gray-200 rounded-lg shadow-lg"></div>
                    </div>
                    <!-- Events/Reserved Dates -->
                    <div class="flex flex-col p-2 space-y-4 overflow-y-auto">
                        <div>
                            <h2 class="text-xl font-semibold mt-4">Reservations</h2>
                        </div>

                        <div id="eventsList" class="bg-white shadow overflow-y-auto sm:rounded-lg flex-1">
                            <ul id="eventsListUl" class="divide-y divide-gray-200 flex flex-col">
                                <?php
                                // Display reservations
                                if ($all_reservation_result->num_rows > 0) {
                                    while ($row = $all_reservation_result->fetch_assoc()) {
                                        echo '<li class="p-4 border-gray-200 border-b">';
                                        echo '<h3 class="text-lg font-bold mb-2">' . htmlspecialchars($row["facility_name"]) . '</h3>';
                                        echo '<p class="text-gray-600 mb-2">Reservation Date: ' . htmlspecialchars($row["reservation_date"]) . '</p>';
                                        echo '<p class="text-gray-600 mb-2">Start Time: ' . htmlspecialchars($row["start_time"]) . '</p>';
                                        echo '<p class="text-gray-600 mb-2">End Time: ' . htmlspecialchars($row["end_time"]) . '</p>';
                                        echo '<p class="text-gray-600 mb-2">End Time: ' . htmlspecialchars($row["purpose"]) . '</p>';
                                    }
                                } else {
                                    echo '<li>No reservations found</li>';
                                }
                                ?>
                            </ul>
                        </div>


                    <div>
                </div>

            </main>            
        </div>
    </div>

    <!-- Logout Modal -->
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
        initialView: 'dayGridMonth',
        events: <?php echo json_encode($reservations); ?>,
        eventDidMount: function(info) {
            // Manipulate the event element's style here
            info.el.style.position = 'absolute';
            info.el.style.left = '0';
            info.el.style.right = '0';
            info.el.style.top = '0';
            info.el.style.bottom = '0';
        },
        eventContent: function(info) {
            return {
                html: `
                    <div style="position: relative; z-index: 1;">
                        ${info.event.title}<br>
                        ${info.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true, seconds: false })}
                        ${info.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true, seconds: false })}
                    </div>
                `
            };
        }
    });

    // Event listener for clicking on FullCalendar events
    calendar.on('eventClick', function(info) {
        console.log('Clicked event:', info.event);
        // Call the showModal function and pass the event details
        showModal(info.event);
    });

    calendar.render();
});
    </script>
</body>
</html>