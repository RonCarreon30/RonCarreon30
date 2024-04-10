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

// Fetch user's department from the database
$head_department = '';
$head_department_sql = "SELECT department FROM users WHERE id = $user_id";
$head_department_result = $conn->query($head_department_sql);
if ($head_department_result->num_rows > 0) {
    $row = $head_department_result->fetch_assoc();
    $head_department = $row['department'];
}

// Fetch reservations from the same department with status "In Review"
$reservation_sql = "SELECT * FROM reservations WHERE user_department = '$head_department' AND reservation_status = 'In Review'";
$reservation_result = $conn->query($reservation_sql);
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
        const calendarEl = document.getElementById('calendar')
        const calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        })
        calendar.render()
      })

    </script>
</head>
<body>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <!-- Component Start -->
        <div class="flex flex-col items-center w-16 h-full overflow-hidden text-blue-200 bg-plv-blue rounded-r-lg">
            <a class="flex items-center justify-center mt-3" href="#">
                <img class="w-8 h-8" src="img/PLV Logo.png" alt="Logo">
            </a>
            <div class="flex flex-col items-center mt-3 border-t border-gray-700">
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="deptHeadDashboard.php">
                    <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                      </svg>
                                          
                </a>
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="deptHeadSched.html">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>                      
                </a>
            </div>
            <div class="flex flex-col items-center mt-2 border-t border-gray-700">
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="#">
                    <svg class="w-6 h-6 stroke-current"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </a>
                <a class="relative flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                      </svg>
                      
                    <span class="absolute top-0 left-0 w-2 h-2 mt-2 ml-2 bg-plv-highlight rounded-full"></span>
                </a>
            </div>
                <!-- Trigger the custom confirmation dialog -->
                <a class="flex items-center justify-center w-16 h-16 mt-auto bg-persian-blue hover:bg-plv-highlight" href="#" onclick="showCustomDialog()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                    </svg>
                </a>
        </div>
        <!-- Component End  -->
        <!-- Content area -->
        <div class="flex flex-col flex-1">
            <!-- Header -->
            <header class="bg-white shadow-lg">
                <!-- Header content -->
                <div class="flex items-center justify-between px-6 py-3 border-b">
                    <h2 class="text-lg font-semibold">Chairperson's Dashboard</h2>
                    <!-- Add any header content here -->
                </div>
            </header>
            <!-- Main content area -->
            <main class="flex-1 p-4">
                <div class="flex h-[560px] flex-row items-center space-x-4">
                    <div class="flex h-full w-8/12 flex-col space-y-2">
                            
                        <!-- For debugging purposes to get session data-->
                        <div class="flex flex-col space-y-2 hidden">
                            <label for="department" class="text-gray-700">Department:</label>
                            <input type="text" id="department" name="department" class="border border-gray-300 rounded-md p-2" value="<?php echo htmlspecialchars($head_department); ?>" readonly>
                        </div>
                        <!-- For debugging purposes to get session data-->
                    </div>

                    <div class="h-full border-l border-gray-300"></div>

                    <div class="flex flex-col h-full w-1/3 space-y-4">
                        <div class="h-1/2">
                            <div id='calendar' class="h-full p-1 text-xs bg-white border border-gray-200 rounded-lg shadow-lg"></div>
                        </div>
                    <!-- Events/Reserved Dates -->
                    <div class="flex flex-col h-full space-y-4 overflow-hidden">
                        <div>
                            <h2 class="font-semibold">Events/Reserved Dates</h2>
                        </div>
                        <div id="eventsList" class="bg-white shadow overflow-hidden sm:rounded-lg flex-1">
                        <ul id="eventsListUl" class="divide-y divide-gray-200 flex flex-col">
    <?php
    // Display reservations
    if ($reservation_result->num_rows > 0) {
        while ($row = $reservation_result->fetch_assoc()) {
            // Add unique IDs to each list item
            $reservationId = $row["id"];
            echo '<li class="p-4 border-gray-200 border-b reservation-item" data-reservation-id="' . $reservationId . '">';
            echo '<h3 class="text-lg font-bold mb-2">' . htmlspecialchars($row["facility_name"]) . '</h3>';
            echo '<p class="text-gray-600 mb-2">Reservation Date: ' . htmlspecialchars($row["reservation_date"]) . '</p>';
            echo '<p class="text-gray-600 mb-2">Start Time: ' . htmlspecialchars($row["start_time"]) . ' - End Time: ' . htmlspecialchars($row["end_time"]) . '</p>';
            echo '<p class="italic">' . htmlspecialchars($row["reservation_status"]) . '</p>';
            // Add accept and decline buttons
            echo '<div class="flex justify-between mt-2">';
            echo '<button onclick="declineReservation(' . $reservationId . ')" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600">Decline</button>';
            echo '<button onclick="acceptReservation(' . $reservationId . ')" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-green-600">Accept</button>';
            echo '</div>';
            echo '</li>';
        }
    } else {
        echo '<li>No reservations found</li>';
    }
    ?>
</ul>

                        </div>
                    </div>
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
    <!-- Modal markup -->
<div id="reservationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md flex flex-col items-center">
        <div id="modalContent" class="font-bold text-xl text-blue-600 z-10">
            <!-- Reservation details will be dynamically added here -->
        </div>
        <div class="flex justify-center mt-5">
            <button onclick="closeModal()" class="mr-4 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-400">Close</button>
        </div>
    </div>
</div>
    <script src="scripts/logout.js"></script>
    <script src="scripts/functions.js"></script>
    <script>
    // Function to show the modal
// Function to show modal with message
function showModal(message) {
    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML = `<p>${message}</p>`;
    document.getElementById('reservationModal').classList.remove('hidden');
}

    // Function to close the modal
    function closeModal() {
        document.getElementById('reservationModal').classList.add('hidden');
    }

//Dept. Head Reservations Functions
// Function to handle accepting reservation
function acceptReservation(reservationId) {
    fetch('update_reservation_status.php?id=' + reservationId + '&status=Pending', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (response.ok) {
            // Reservation accepted successfully, show success modal
            showModal('Reservation accepted');
        } else {
            // Handle error
            showModal('Error accepting reservation');
        }
    });
}

// Function to handle declining reservation
function declineReservation(reservationId) {
    fetch('update_reservation_status.php?id=' + reservationId + '&status=Declined', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (response.ok) {
            // Reservation declined successfully, show success modal
            showModal('Reservation declined');
        } else {
            // Handle error
            showModal('Error declining reservation');
        }
    });
}

// Function to show modal with message
function showModal(message) {
    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML = `<p>${message}</p>`;
    document.getElementById('reservationModal').classList.remove('hidden');
}


</script>
</body>
</html>
