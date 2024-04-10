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
$reservation_sql = "SELECT * FROM reservations WHERE user_id = $user_id";
$reservation_result = $conn->query($reservation_sql);

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
        <!-- Sidebar -->
        <!-- Component Start -->
        <div class="fixed flex flex-col items-center w-16 h-full overflow-hidden text-blue-200 bg-plv-blue rounded-r-lg">
            <a class="flex items-center justify-center mt-3" href="#">
                <img class="w-8 h-8" src="img/PLV Logo.png" alt="Logo">
            </a>
            <div class="flex flex-col items-center mt-3 border-t border-gray-700">
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="#">
                    <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </a>
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                      </svg>
                                          
                </a>
                <a class="flex items-center justify-center w-12 h-12 mt-2 rounded hover:bg-persian-blue" href="#">
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
        <div class="pl-16 flex flex-col flex-1">
            <!-- Header -->
            <header class="bg-white shadow-lg">
                <!-- Header content -->
                <div class="flex items-center justify-between px-6 py-3 border-b">
                    <h2 class="text-lg font-semibold">Facility Reservation</h2>
                    <!-- Add any header content here -->
                </div>
            </header>
            <!-- Main content area -->
            <main class="flex-1 p-4">
                <!-- Search and List of Facilities-->
                <div class="flex h-[560px] flex-row items-center space-x-4">
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
                                    echo '<p class="text-gray-600 mb-2 ' . $statusClass . ' ">' . htmlspecialchars($row["status"]) . '</p>';
                                    echo '<br><p class="text-gray-600">' . htmlspecialchars($row["descri"]) . '</p>';
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
                    <div class="flex flex-col h-full w-1/3 space-y-4"> <!-- Updated flex and added flex-col -->
    <div>
        <h2 class="text-xl font-semibold mb-4">Events/Reserved Dates</h2>
    </div>

    <div id="eventsList" class="bg-white shadow overflow-y-auto sm:rounded-lg flex-1"> <!-- Added flex-1 -->
        <ul id="eventsListUl" class="divide-y divide-gray-200 flex flex-col"> <!-- Added flex and flex-col -->
            <?php
            // Display reservations
            if ($reservation_result->num_rows > 0) {
                while ($row = $reservation_result->fetch_assoc()) {
                    echo '<li class="p-4 border-gray-200 border-b">'; // Added border class
                    echo '<h3 class="text-lg font-bold mb-2">' . htmlspecialchars($row["facility_name"]) . '</h3>';
                    echo '<p class="text-gray-600 mb-2">Reservation Date: ' . htmlspecialchars($row["reservation_date"]) . '</p>';
                    echo '<p class="text-gray-600 mb-2">Start Time: ' . htmlspecialchars($row["start_time"]) . '</p>';
                    echo '<p class="text-gray-600 mb-2">End Time: ' . htmlspecialchars($row["end_time"]) . '</p>';
                    echo '<p class="italic">' . htmlspecialchars($row["reservation_status"]) . '</p>';
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


    <script src="scripts/logout.js"></script>
    <script>
document.addEventListener("DOMContentLoaded", function() {
    // Show reservation modal on clicking facility card
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

    // Close modal when close button is clicked
    closeModalBtn.addEventListener('click', function() {
        reservationModal.classList.add('hidden');
    });

    // Close modal when clicked outside of modal
    window.addEventListener('click', function(event) {
        if (event.target === reservationModal) {
            reservationModal.classList.add('hidden');
        }
    });

    // Filter facilities based on search input
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
    function refreshPage() {
        location.reload();
    }
// Handle form submission
const reserveButton = document.getElementById('reserveButton');
reserveButton.addEventListener('click', function() {
    // Validate form fields before submission
    const facilityName = document.getElementById('facilityName').value;
    const reservationDate = document.getElementById('reservationDate').value;
    const startTime = document.getElementById('startTime').value;
    const endTime = document.getElementById('endTime').value;

    // Check if any required field is empty
    if (facilityName === '' || reservationDate === '' || startTime === '' || endTime === '') {
        alert('Please fill in all required fields.');
        return;
    }

    // If all fields are filled, submit the form
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
        reservationModal.classList.add('hidden');
        // Show success modal
        const successModal = document.getElementById('successModal');
        successModal.classList.remove('hidden');
        setTimeout(refreshPage, 3000)
    })

    .catch(error => {
        // Handle error
        console.error('Error submitting reservation:', error);
        // Optionally, display an error message to the user
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
</script>

</body>
</html>