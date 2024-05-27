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
    if ($_SESSION['role'] !== 'Student Rep') {
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

    // Query to fetch reservations for the current user
    $user_id = $_SESSION['user_id'];
    // Query to fetch reservations for the current user and order them by reservation date in descending order
    $my_reservation_sql = "SELECT * FROM reservations WHERE user_id = $user_id ORDER BY created_at DESC";
    $my_reservation_result = $conn->query($my_reservation_sql);


    // Fetch user's department from the database
    $user_department = '';
    $user_department_sql = "SELECT department FROM users WHERE id = $user_id";
    $user_department_result = $conn->query($user_department_sql);
    if ($user_department_result->num_rows > 0) {
        $row = $user_department_result->fetch_assoc();
        $user_department = $row['department'];
    }

    // Fetch facilities from the database and display them in cards
    $facility_sql = "SELECT * FROM facilities";
    $facility_result = $conn->query($facility_sql);

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
            <div id="sidebar-container">
                <?php include 'sidebar.php'; ?>
            </div>
            
            <!-- Content area -->
            <div class="flex flex-col flex-1">
                <!-- Header -->
                <header class="bg-white shadow-lg">
                    <!-- Header content -->
                    <div class="flex items-center justify-between px-6 py-3 border-b">
                        <h2 class="text-lg font-semibold">Facility Reservation</h2>
                    </div>
                </header>
                <!-- Main content area -->
                <main class="flex-1 p-4 overflow-y-auto">
                    <!-- Search and List of Facilities-->
                    <div class="flex h-full flex-row items-center space-x-4">
                        <div class="flex h-full w-8/12 flex-col space-y-2">
                            <!-- Search Input -->
                            <div class="mb-4">
                                <input type="text" id="searchInput" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-400" placeholder="Search facilities...">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="facilityContainer">
                            <?php
                                // Fetch facilities from the database and display them in cards
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

                                // Query to fetch facilities
                                $sql = "SELECT * FROM facilities";
                                $result = $conn->query($sql);

                                // Display facilities in cards
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        // Check if the facility is available or unavailable
                                        $statusClass = $row["status"] == "Unavailable" ? "text-red-600 pointer-events-none" : ""; // Add opacity and disable pointer events if status is "unavailable"

                                        // Output facility card with appropriate styling
                                        echo '<div class="bg-white p-4 rounded-md shadow-md cursor-pointer facility-card ' . $statusClass . '" data-facility-name="' . htmlspecialchars($row["facility_name"]) . '">';
                                        echo '<h3 class="text-lg font-bold mb-2">' . htmlspecialchars($row["facility_name"]) . '</h3>';
                                        echo '<p class="text-gray-600 mb-2">' . htmlspecialchars($row["building"]) . '</p>';
                                        echo '<p class="text-gray-600 mb-2 ' . $statusClass . '">' . htmlspecialchars($row["status"]) . '</p>';
                                        echo '<br><p class="text-gray-600">' . htmlspecialchars($row["descri"]) . '</p>';
                                        if ($row["status"] !== "Unavailable") {
                                            echo '<button class="bg-blue-500 text-white rounded-md px-4 py-2 mt-2 hover:bg-blue-600 reserve-button">Reserve</button>';
                                        }
                                        echo '</div>';
                                    }
                                } else {
                                    echo "No facilities found";
                                }

                                // Close connection
                                $conn->close();
                            ?>
                            </div>
                        </div>

                        <!-- Divider Line -->
                        <div class="h-full border-l border-gray-300"></div>

                        <!-- Events/Reserved Dates -->
                        <div class="flex flex-col h-full w-1/3 space-y-4">
                            <div class="h-1/2">
                                <div id='calendar' class="h-full p-1 text-xs bg-white border border-gray-200 rounded-lg shadow-lg"></div>
                            </div>

                            <!-- Events/Reserved Dates -->
                            <div class="flex flex-col space-y-4 overflow-y-auto">
                                <div>
                                    <h2 class="text-xl font-semibold mt-4">My Reservation/s</h2>
                                </div>

    <div id="eventsList" class="bg-white shadow overflow-y-auto sm:rounded-lg flex-1">
        <ul id="eventsListUl" class="divide-y divide-gray-200 flex flex-col">
            <?php
            // Display reservations
            if ($my_reservation_result->num_rows > 0) {
                while ($row = $my_reservation_result->fetch_assoc()) {
                    echo '<li class="p-4 border-gray-200 border-b">';
                    echo '<h3 class="text-lg font-bold mb-2">' . htmlspecialchars($row["facility_name"]) . '</h3>';
                    echo '<p class="text-gray-600 mb-2">Reservation Date: ' . htmlspecialchars($row["reservation_date"]) . '</p>';
                    echo '<p class="text-gray-600 mb-2">Start Time: ' . htmlspecialchars($row["start_time"]) . '</p>';
                    echo '<p class="text-gray-600 mb-2">End Time: ' . htmlspecialchars($row["end_time"]) . '</p>';
                    
                    // Check if the reservation is declined and display rejection reason if available
                    if ($row["reservation_status"] === 'Declined') {
                        echo '<p class="text-red-600 font-bold">Reservation Status: ' . htmlspecialchars($row["reservation_status"]) . '</p>';
                        echo '<p class="text-red-600 font-bold">Rejection Reason: ' . htmlspecialchars($row["rejection_reason"]) . '</p>';
                    } else {
                        echo '<p class="italic">Reservation Status: ' . htmlspecialchars($row["reservation_status"]) . '</p>';
                    }
                    
                    // Add buttons for deleting and editing reservations
                    echo '<div class="flex justify-between">';
                    echo '<button onclick="editReservation(' . $row["id"] . ')" class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-yellow-600">Edit</button>';
                    echo '<button onclick="deleteReservation(' . $row["id"] . ')" class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600">Delete</button>';
                    echo '</div>';
                    
                    echo '</li>';
                }
            } else {
                echo '<li>No reservations found</li>';
            }
            ?>
        </ul>
    </div>


                            <div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <!-- Reservation Modal -->
        <div id="reservationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-8 rounded-md shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-semibold">Reserve Facility</h2>
                    <button id="closeModal" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="reservationForm" class="space-y-4">
                    <div class="flex mb-4 gap-2">
                        <div class="w-1/2">
                            <div class="flex flex-col space-y-2">
                                <label for="facilityName" class="text-gray-700">Facility Name:</label>
                                <input type="text" id="facilityName" name="facilityName" class="border border-gray-300 rounded-md p-2" readonly required>
                            </div>
                        </div>
                        <div class="w-1/2">
                            <div class="flex flex-col space-y-2">
                                <label for="reservationDate" class="text-gray-700">Reservation Date:</label>
                                <input type="date" id="reservationDate" name="reservationDate" class="border border-gray-300 rounded-md p-2" required>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-2 hidden">
                        <label for="department" class="text-gray-700">Department:</label>
                        <input type="text" id="department" name="department" class="border border-gray-300 rounded-md p-2" value="<?php echo htmlspecialchars($user_department); ?>" readonly>
                    </div>
                    <div class="flex mb-4 gap-2">
                        <div class="w-1/2">
                            <div class="flex flex-col space-y-2">
                                <label for="startTime" class="text-gray-700">Starting Time:</label>
                                <input type="time" id="startTime" name="startTime" class="border border-gray-300 rounded-md p-2" required>
                            </div>
                        </div>
                        <div class="w-1/2">
                            <div class="flex flex-col space-y-2">
                                <label for="endTime" class="text-gray-700">End Time:</label>
                                <input type="time" id="endTime" name="endTime" class="border border-gray-300 rounded-md p-2" required>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-2">
        <label for="purpose" class="text-gray-700">Purpose:</label>
        <input type="text" id="purpose" name="purpose" class="border border-gray-300 rounded-md p-2">
    </div>

                    <div class="flex flex-col space-y-2">
                        <label for="additionalInfo" class="text-gray-700">Additional Information:</label>
                        <textarea id="additionalInfo" name="additionalInfo" class="border border-gray-300 rounded-md p-2"></textarea>
                    </div>
                    <!-- Add more form fields as needed -->
                    <div class="flex justify-between">
                        <button type="button" id="reserveButton" class="bg-blue-500 text-white rounded-md px-4 py-2 hover:bg-blue-600">Reserve</button>
                    </div>
                </form>
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

        <!-- Error Modal -->
        <div id="errorModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-8 rounded-md shadow-md">
                <h2 class="text-xl font-semibold mb-4">Validation Errors</h2>
                <ul id="errorList" class="text-red-600">
                    <!-- Validation errors will be inserted here dynamically -->
                </ul>
                <button id="closeErrorModal" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Close</button>
            </div>
        </div>

        <!-- Success Modal -->
        <div id="successModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-8 rounded-md shadow-md">
                <h2 class="text-xl font-semibold mb-4">Success</h2>
                <p class="text-green-600">Reservation sent to Department Head for review</p>
                <button id="closeSuccessModal" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Close</button>
            </div>
        </div>

    <!-- Edit Reservation Modal -->
    <div id="editReservationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-md">
            <div class="font-bold text-xl mb-4">Edit Reservation</div>
            <form id="editReservationForm" class="space-y-4">
                <input type="hidden" id="editReservationId">
                <div class="flex flex-col space-y-2">
                    <label for="editFacilityName" class="text-gray-700">Facility Name:</label>
                    <input type="text" id="editFacilityName" name="facility_name" class="border border-gray-300 bg-gray-300 rounded-md p-2" readonly disabled>
                </div>
                <div class="flex flex-col space-y-2">
                    <label for="editReservationDate" class="text-gray-700">Reservation Date:</label>
                    <input type="date" id="editReservationDate" name="reservation_date" class="border border-gray-300 rounded-md p-2" required>
                </div>
                <div class="flex flex-col space-y-2">
                    <label for="editStartTime" class="text-gray-700">Start Time:</label>
                    <input type="time" id="editStartTime" name="start_time" class="border border-gray-300 rounded-md p-2" required>
                </div>
                <div class="flex flex-col space-y-2">
                    <label for="editEndTime" class="text-gray-700">End Time:</label>
                    <input type="time" id="editEndTime" name="end_time" class="border border-gray-300 rounded-md p-2" required>
                </div>
            </form>
            <div class="flex justify-center mt-5">
                <button onclick="closeEditModal()" class="mr-4 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-400">Cancel</button>
                <button onclick="updateReservation()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Save</button>
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

            document.addEventListener("DOMContentLoaded", function() {
                const facilityCards = document.querySelectorAll('.facility-card');
                const reservationModal = document.getElementById('reservationModal');
                const closeModalBtn = document.getElementById('closeModal');
                const facilityNameInput = document.getElementById('facilityName');
                const reservationForm = document.getElementById('reservationForm');
                const searchInput = document.getElementById('searchInput');
                const facilityContainer = document.getElementById('facilityContainer');

                facilityCards.forEach(card => {
                    card.addEventListener('click', function() {
                        const facilityName = this.getAttribute('data-facility-name');
                        facilityNameInput.value = facilityName;
                        reservationModal.classList.remove('hidden');
                    });
                });

                closeModalBtn.addEventListener('click', function() {
                    reservationModal.classList.add('hidden');
                });

                window.addEventListener('click', function(event) {
                    if (event.target === reservationModal) {
                        reservationModal.classList.add('hidden');
                    }
                });

                searchInput.addEventListener('input', function() {
                    const searchTerm = searchInput.value.toLowerCase();
                    const facilityCards = facilityContainer.querySelectorAll('.facility-card');

                    facilityCards.forEach(card => {
                        const facilityName = card.dataset.facilityName.toLowerCase();
                        if (facilityName.includes(searchTerm)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });

            // Handle form submission
            const reserveButtons = document.querySelectorAll('.reserve-button');
            reserveButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const facilityName = this.closest('.facility-card').getAttribute('data-facility-name');
                    facilityNameInput.value = facilityName;
                    reservationModal.classList.remove('hidden');
                });
            });

            const refreshPage = () => {
                location.reload();
            };

            const reserveButton = document.getElementById('reserveButton');
    reserveButton.addEventListener('click', function() {
        const facilityName = document.getElementById('facilityName').value;
        const reservationDate = document.getElementById('reservationDate').value;
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;

        if (facilityName === '' || reservationDate === '' || startTime === '' || endTime === '') {
            alert('Please fill in all required fields.');
            return;
        }

        const reservationForm = document.getElementById('reservationForm');
        const formData = new FormData(reservationForm);

        fetch('reserve_facility.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Parse response as JSON
        })
        .then(data => {
            if (data.success) {
                // Reservation was successful, show success modal
                reservationModal.classList.add('hidden');
                const successModal = document.getElementById('successModal');
                successModal.classList.remove('hidden');
                setTimeout(refreshPage, 3000);
            } else {
                // Reservation failed, show error modal with error message
                const errorModal = document.getElementById('errorModal');
                const errorList = document.getElementById('errorList');
                errorList.innerHTML = '<li>' + data.error + '</li>';
                errorModal.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error submitting reservation:', error);
        });
    });


            // Close error modal
            const closeErrorModalBtn = document.getElementById('closeErrorModal');
            closeErrorModalBtn.addEventListener('click', function() {
                const errorModal = document.getElementById('errorModal');
                errorModal.classList.add('hidden');
            });

            // Close success modal
            const closeSuccessModalBtn = document.getElementById('closeSuccessModal');
            closeSuccessModalBtn.addEventListener('click', function() {
                const successModal = document.getElementById('successModal');
                successModal.classList.add('hidden');
            });
        });

    // Function to show edit reservation modal
    function editReservation(reservationId) {
        fetch('get_reservation_details.php?id=' + reservationId)
            .then(response => response.json())
            .then(data => {
                // Populate the form with existing reservation details
                document.getElementById('editFacilityName').value = data.facility_name;
                document.getElementById('editReservationDate').value = data.reservation_date;
                document.getElementById('editStartTime').value = data.start_time;
                document.getElementById('editEndTime').value = data.end_time;
                document.getElementById('editReservationId').value = reservationId;

                // Show the edit modal
                document.getElementById('editReservationModal').classList.remove('hidden');
            })
            .catch(error => console.error('Error fetching reservation details:', error));
    }

    // Function to close edit reservation modal
    function closeEditModal() {
        document.getElementById('editReservationModal').classList.add('hidden');
    }

    // Function to handle update reservation form submission
    function updateReservation() {
        const reservationId = document.getElementById('editReservationId').value;
        const reservationDate = document.getElementById('editReservationDate').value;
        const startTime = document.getElementById('editStartTime').value;
        const endTime = document.getElementById('editEndTime').value;

        const data = {
            reservation_date: reservationDate,
            start_time: startTime,
            end_time: endTime,
            status: 'In Review' // Ensure status update
        };

        fetch('update_declined_reservation.php?id=' + reservationId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                alert(data.success);
                closeEditModal();
                location.reload(); // Reload the page to reflect changes
            }
        })
        .catch(error => {
            console.error('Error updating reservation:', error);
            alert('Error updating reservation.');
        });
    }


    // Function to confirm deletion of reservation
    function deleteReservation(reservationId) {
        if (confirm('Are you sure you want to delete this reservation?')) {
            fetch('delete_reservation.php?id=' + reservationId, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page or update the reservation list
                    location.reload();
                } else {
                    alert('Error deleting reservation');
                }
            })
            .catch(error => console.error('Error deleting reservation:', error));
        }
    }

        
    </script>

    </body>
    </html>

