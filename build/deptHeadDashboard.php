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

// Fetch the user data from the database
$user_query = "SELECT * FROM users WHERE id = '$user_id'";
$user_result = $conn->query($user_query);
$user_data = $user_result->fetch_assoc();

// Fetch user's department from the database
$head_department = '';
$head_department_sql = "SELECT department FROM users WHERE id = $user_id";
$head_department_result = $conn->query($head_department_sql);
if ($head_department_result->num_rows > 0) {
    $row = $head_department_result->fetch_assoc();
    $head_department = $row['department'];
}

// Fetch reservations with status "In Review" from the same department
$review_reservation_sql = "SELECT * FROM reservations WHERE user_department = '$head_department' ORDER BY created_at DESC";
$review_reservation_result = $conn->query($review_reservation_sql);


$reservations = [];
$reservation_sql = "SELECT * FROM reservations WHERE reservation_status = 'Reserved'";
$reservation_result = $conn->query($reservation_sql);

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
</head>
<body>
    <div class="flex h-screen bg-gray-100">

        <div id="sidebar-container">
            <?php include 'sidebar.php'; ?>
        </div>
        
        <div class="flex flex-col flex-1">
            <header class="bg-white shadow-lg">
                <div class="flex items-center justify-between px-6 py-3 border-b">
                    <h2 class="text-lg font-semibold">Chairperson's Dashboard</h2>
                    <!-- Add any header content here -->
                </div>
            </header>
            <!-- For debugging purposes to get session data-->
            <div class="flex flex-col space-y-2 hidden">
                <label for="department" class="text-gray-700">Department:</label>
                <input type="text" id="department" name="department" class="border border-gray-300 rounded-md p-2" value="<?php echo htmlspecialchars($head_department); ?>" readonly>
            </div>
            <!-- For debugging purposes to get session data-->
            <!-- Main content area -->
            <main class="flex flex-1 p-4 h-screen overflow-y-auto">
                <div class="w-3/4 pr-4">
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
                            <i class="fas fa-calendar-alt fa-3x w-1/4 text-blue-600"></i>
                            <div class="ml-4 w-3/4">
                                <h2 class="text-lg font-bold">Upcoming Reservations</h2>
                                <?php
                                // Fetch count of upcoming reservations for the current user's department
                                $reservation_count_sql = "SELECT COUNT(*) AS count FROM reservations WHERE user_department = '$head_department' AND reservation_date >= CURDATE()";
                                $reservation_count_result = $conn->query($reservation_count_sql);
                                
                                if ($reservation_count_result) {
                                    $row = $reservation_count_result->fetch_assoc();
                                    $reservation_count = $row['count'];
                                    echo '<p class="text-2xl">' . $reservation_count . '</p>';
                                } else {
                                    echo '<p class="text-2xl">0</p>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="flex items-center rounded bg-white p-6 shadow-md h-40">
                            <i class="fas fa-exclamation-triangle fa-3x w-1/4 text-red-600"></i>
                            <div class="ml-4 w-3/4">
                                <h2 class="text-lg font-bold">Declined Reservation</h2>
                                <?php
                                // Fetch count of unapproved room loadings for the current user's department
                                $unapproved_count_sql = "SELECT COUNT(*) AS count FROM reservations WHERE user_department = '$head_department' AND reservation_status = 'In Review'";
                                $unapproved_count_result = $conn->query($unapproved_count_sql);
                                
                                if ($unapproved_count_result) {
                                    $row = $unapproved_count_result->fetch_assoc();
                                    $unapproved_count = $row['count'];
                                    echo '<p class="text-2xl">' . $unapproved_count . '</p>';
                                } else {
                                    echo '<p class="text-2xl">0</p>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="flex items-center rounded bg-white p-6 shadow-md h-40">
                            <i class="fas fa-calendar-check fa-3x w-1/4 text-green-600"></i>
                            <div class="ml-4 w-3/4">
                                <h2 class="text-lg font-bold">Total Classes Scheduled</h2>

                            </div>
                        </div>
                        <div class="flex items-center rounded bg-white p-6 shadow-md h-40">
                            <i class="fas fa-calendar-times fa-3x w-1/4 text-yellow-600"></i>
                            <div class="ml-4 w-3/4">
                                <h2 class="text-lg font-bold">Total Classes Not Scheduled</h2>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="h-full border-l border-gray-300"></div>

                <div class="flex flex-col h-full w-1/3 space-y-4">
                    <div class="h-1/2">
                        <div id='calendar' class="h-full p-1 text-xs bg-white border border-gray-200 rounded-lg shadow-lg"></div>
                    </div>
                    <!-- Events/Reserved Dates -->
                    <div class="flex flex-col space-y-4 overflow-y-auto">
                        <div>
                            <h2 class="font-semibold">Events/Reserved Dates</h2>
                        </div>
                        <div id="eventsList" class="bg-white shadow overflow-y-auto sm:rounded-lg flex-1">
                            <ul id="eventsListUl" class="divide-y divide-gray-200 flex flex-col">
                                <?php
                                // Display reservations
                                if ($review_reservation_result->num_rows > 0) {
                                    // Reset pointer to the beginning of the result set
                                    mysqli_data_seek($review_reservation_result, 0);
                                    
                                    while ($row = $review_reservation_result->fetch_assoc()) {
                                        // Add unique IDs to each list item
                                        $reservationId = $row["id"];
                                        echo '<li class="p-4 border-gray-200 border-b reservation-item" data-reservation-id="' . $reservationId . '">';
                                        echo '<h3 class="text-lg font-bold mb-2">' . htmlspecialchars($row["facility_name"]) . '</h3>';
                                        echo '<p class="text-gray-600 mb-2">Reservation Date: ' . htmlspecialchars($row["reservation_date"]) . '</p>';
                                        echo '<p class="text-gray-600 mb-2">Start Time: ' . htmlspecialchars($row["start_time"]) . ' - End Time: ' . htmlspecialchars($row["end_time"]) . '</p>';
                                        echo '<p class="italic">' . htmlspecialchars($row["reservation_status"]) . '</p>';
                                        
                                        // Conditionally show accept and decline buttons only for "In Review" or "Declined" reservations
                                        if ($row["reservation_status"] === "In Review") {
                                            echo '<div class="flex justify-between mt-2">';
                                            echo '<button onclick="declineReservation(' . $reservationId . ')" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600">Decline</button>';
                                            echo '<button onclick="acceptReservation(' . $reservationId . ')" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-green-600">Accept</button>';
                                            echo '</div>';
                                        }
                                        
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

<!-- Reservation Modal -->
<div id="reservationsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md">
        <div class="font-bold text-xl mb-4">Reservation Details</div>
        <div id="modalContent" class="text-gray-800">
            <p><strong>Facility Name:</strong> <span id="facilityName"></span></p>
            <p><strong>Reservation Date:</strong> <span id="reservationDate"></span></p>
            <p><strong>Start Time:</strong> <span id="startTime"></span></p>
            <p><strong>End Time:</strong> <span id="endTime"></span></p>
            <!-- Add more details as needed -->
        </div>
        <div class="flex justify-center mt-5">
            <button onclick="closeModal()" class="mr-4 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-400">Close</button>
        </div>
    </div>
</div>
<!-- Modal for rejection reason-->
<div id="rejectionReasonForm" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-8 rounded-md shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold">Enter reason for rejection:</h2>
            </div>
            <div>
                <form id="reservationForm" class="space-y-4">
                    <div class="flex flex-col space-y-2">
                        <textarea id="rejectionReason" name="rejectionReason" rows="3" class="border border-gray-300 rounded-md p-2" required></textarea>
                    </div>
                </form>
                <button onclick="hideRejectModal()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Cancel</button>
                <button id="confirmRejectionButton" class="px-4 py-2 mt-2 bg-red-500 text-white rounded-lg hover:bg-red-600">OK</button>
            </div>
    </div>
</div>
<!-- HTML for custom confirmation dialog -->
<div id="confirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md flex flex-col items-center">
        <p id="confirmationMessage" class="text-lg text-slate-700 font-semibold mb-4"></p>
        <div class="flex justify-center mt-5">
            <button onclick="cancelAction()" class="mr-4 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Cancel</button>
            <button onclick="confirmAction()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Confirm</button>
        </div>
    </div>
</div>

    <script src="scripts/logout.js"></script>
    <script src="scripts/functions.js"></script>
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

    // Event listener for clicking on FullCalendar events
    calendar.on('eventClick', function(info) {
        console.log('Clicked event:', info.event);
    // Call the showModal function and pass the event details
    showModal(info.event);
    });

    calendar.render();
});


// Function to show modal with reservation details
function showModal(event) {
    console.log('Showing modal for event:', event);
    const modal = document.getElementById('reservationsModal');
    const modalContent = modal.querySelector('#modalContent');

    // Convert start and end dates to local time
    const startDate = new Date(event.start);
    const endDate = new Date(event.end);

    // Format options for date and time
    const dateOptions = {
        weekday: 'short', 
        year: 'numeric', 
        month: 'numeric', 
        day: 'numeric',
    };

    const timeOptions = {
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
    };

    modalContent.innerHTML = `
        <p><strong>Facility Name:</strong> ${event.title}</p>
        <p><strong>Reservation Date:</strong> ${startDate.toLocaleDateString(undefined, dateOptions)}</p>
        <p><strong>Start Time:</strong> ${startDate.toLocaleTimeString(undefined, timeOptions)}</p>
        <p><strong>End Time:</strong> ${endDate.toLocaleTimeString(undefined, timeOptions)}</p>
        <!-- Add more details as needed -->
    `;

    modal.classList.remove('hidden');
}


// Function to close the modal
function closeModal() {
    const modal = document.getElementById('reservationsModal');
    modal.classList.add('hidden');
}


// Function to hide success modal
function hideRejectModal() {
    const rejectionReasonForm = document.getElementById('rejectionReasonForm');
    rejectionReasonForm.classList.add('hidden');
}

//Dept. Head Reservations Functions
// Function to show confirmation modal
function showConfirmation(message, callback) {
    const confirmationModal = document.getElementById('confirmationModal');
    const confirmationMessage = document.getElementById('confirmationMessage');
    confirmationMessage.innerText = message;
    confirmationModal.classList.remove('hidden');
    // Set callback function for confirmation action
    confirmActionCallback = callback;
}

// Function to hide confirmation modal
function hideConfirmation() {
    const confirmationModal = document.getElementById('confirmationModal');
    confirmationModal.classList.add('hidden');
}

// Function to handle confirmation action
function confirmAction() {
    hideConfirmation();
    if (confirmActionCallback) {
        confirmActionCallback();
    }
}

// Function to handle cancellation of action
function cancelAction() {
    hideConfirmation();
}

let confirmActionCallback;

// Function to handle accepting reservation
function acceptReservation(reservationId) {
    showConfirmation('Are you sure you want to accept this reservation?', function() {
        fetch('update_reservation_status.php?id=' + reservationId + '&status=Pending', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                // Reload the page after successful acceptance
                location.reload();
            } else {
                // Handle error
                showModal({ title: 'Error accepting reservation' });
            }
        });
    });
}
function declineReservation(reservationId) {
    console.log("Decline button clicked");
    // Show rejection reason form
    const rejectionReasonForm = document.getElementById('rejectionReasonForm');
    rejectionReasonForm.classList.remove('hidden');

    // Handle confirmation after inputting rejection reason
    const confirmButton = document.getElementById('confirmRejectionButton');
    confirmButton.onclick = function() {
        // Get rejection reason from the form
        const rejectionReason = document.getElementById('rejectionReason').value;

        // Show confirmation message before declining
        showConfirmation('Are you sure you want to decline this reservation?', function() {
            // Send rejection reason and decline reservation
            fetch('update_reservation_status.php?id=' + reservationId + '&status=Declined', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    reason: rejectionReason
                })
            })
            .then(response => {
                if (response.ok) {
                    // Reload the page after successful decline
                    location.reload();
                } else {
                    // Handle error
                    showModal({ title: 'Error declining reservation' });
                }
            });
        });
    };
}

</script>

</body>
</html>
