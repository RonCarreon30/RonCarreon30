<?php
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    if (empty($_POST['reservationDate']) || empty($_POST['startTime']) || empty($_POST['endTime']) || empty($_POST['facilityName']) || empty($_POST['department']) || empty($_POST['purpose'])) {
        // Return error response if any required field is empty
        echo json_encode(array("success" => false, "error" => "Reservation date, start time, end time, facility name, department, and purpose are required."));
        exit();
    }

    // Continue with form processing if all required fields are provided
    $user_id = $_SESSION['user_id'];
    $reservation_date = $_POST['reservationDate'];
    $start_time = $_POST['startTime'];
    $end_time = $_POST['endTime'];
    $additional_info = $_POST['additionalInfo'];
    $facility_name = $_POST['facilityName']; // Get facility name from the form
    $department = $_POST['department']; // Get department from the form
    $purpose = $_POST['purpose']; // Get purpose from the form
    $reservation_status = 'In Review'; // Set reservation status to 'In Review'

    // Get facility ID based on facility name
    $facility_id = getFacilityId($facility_name);

    if ($facility_id !== false) {
        // Insert reservation into the database
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

        // Prepare SQL statement
        $sql = "INSERT INTO reservations (user_id, user_department, facility_id, facility_name, reservation_date, start_time, end_time, additional_info, reservation_status, purpose) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isisssssss", $user_id, $department, $facility_id, $facility_name, $reservation_date, $start_time, $end_time, $additional_info, $reservation_status, $purpose);

        // Execute SQL statement
        if ($stmt->execute()) {
            // Reservation saved successfully
            echo json_encode(array("success" => true));
        } else {
            // Error occurred while saving reservation
            echo json_encode(array("success" => false, "error" => "Error: " . $conn->error));
        }

        // Close connection
        $stmt->close();
        $conn->close();
    } else {
        // Facility not found
        echo json_encode(array("success" => false, "error" => "Facility not found"));
    }
} else {
    // Handle invalid request method
    echo json_encode(array("success" => false, "error" => "Invalid request method"));
}

// Function to get facility ID based on facility name
function getFacilityId($facility_name) {
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

    // Prepare SQL statement
    $sql = "SELECT id FROM facilities WHERE facility_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $facility_name);

    // Execute SQL statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($facility_id);

    // Fetch result
    $stmt->fetch();

    // Close statement and connection
    $stmt->close();
    $conn->close();

    return isset($facility_id) ? $facility_id : false;
}
?>
