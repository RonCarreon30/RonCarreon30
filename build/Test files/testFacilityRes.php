<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Facility Reservation</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Facility Reservation</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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
            while($row = $result->fetch_assoc()) {
                echo '<div class="bg-white p-4 rounded-md shadow-md cursor-pointer facility-card" data-facility-name="' . htmlspecialchars($row["facility_name"]) . '">';
                echo '<h3 class="text-lg font-bold mb-2">' . htmlspecialchars($row["facility_name"]) . '</h3>';
                echo '<p class="text-gray-600 mb-2">' . htmlspecialchars($row["building"]) . '</p>';
                echo '<p class="text-gray-600">' . htmlspecialchars($row["descri"]) . '</p>';
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

<!-- Reservation Modal -->
<div id="reservationModal" class="fixed top-0 left-0 w-full h-full bg-gray-800 bg-opacity-50 hidden">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-8 rounded-md shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Reserve Facility</h2>
            <button id="closeModal" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="reservationForm" method="post" action="reserve_facility.php" class="space-y-4">
            <div class="flex mb-4 gap-2">
                <div class="w-1/2">
                    <div class="flex flex-col space-y-2">
                        <label for="facilityName" class="text-gray-700">Facility Name:</label>
                        <input type="text" id="facilityName" name="facilityName" class="border border-gray-300 rounded-md p-2" readonly>
                    </div>
                </div>
                <div class="w-1/2">
                    <div class="flex flex-col space-y-2">
                        <label for="reservationDate" class="text-gray-700">Reservation Date:</label>
                        <input type="date" id="reservationDate" name="reservationDate" class="border border-gray-300 rounded-md p-2">
                    </div>
                </div>
            </div>
            <div class="flex mb-4 gap-2">
                <div class="w-1/2">
                    <div class="flex flex-col space-y-2">
                        <label for="startTime" class="text-gray-700">Starting Time:</label>
                        <input type="time" id="startTime" name="startTime" class="border border-gray-300 rounded-md p-2">
                    </div>
                </div>
                <div class="w-1/2">
                    <div class="flex flex-col space-y-2">
                        <label for="endTime" class="text-gray-700">End Time:</label>
                        <input type="time" id="endTime" name="endTime" class="border border-gray-300 rounded-md p-2">
                    </div>
                </div>
            </div>


            <div class="flex flex-col space-y-2">
                <label for="additionalInfo" class="text-gray-700">Additional Information:</label>
                <textarea id="additionalInfo" name="additionalInfo" class="border border-gray-300 rounded-md p-2"></textarea>
            </div>
            <!-- Add more form fields as needed -->
            <button type="submit" class="bg-blue-500 text-white rounded-md px-4 py-2 hover:bg-blue-600">Reserve</button>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Show reservation modal on clicking facility card
    const facilityCards = document.querySelectorAll('.facility-card');
    const reservationModal = document.getElementById('reservationModal');
    const closeModalBtn = document.getElementById('closeModal');
    const facilityNameInput = document.getElementById('facilityName');
    const reservationForm = document.getElementById('reservationForm');

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

    // Prevent default form submission and handle reservation submission
    reservationForm.addEventListener('submit', function(event) {
        event.preventDefault();
        // Add AJAX logic here to submit reservation data to the server
        // You can use fetch API or XMLHttpRequest to send data to PHP script
    });
});
</script>

</body>
</html>
