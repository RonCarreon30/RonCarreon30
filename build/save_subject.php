<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if subjectName and subjectCode arrays are set
    if(isset($_POST['subjectName']) && isset($_POST['subjectCode'])) {
        // Database connection
        include 'config.php';

        // Get the arrays of subject names and subject codes
        $subjectNames = $_POST['subjectName'];
        $subjectCodes = $_POST['subjectCode'];

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO subjects (subject_name, subject_code) VALUES (?, ?)");

        // Initialize variables to store errors
        $successMessage = "";
        $errorMessage = "";

        // Flag to track if any subject was successfully inserted
        $success = false;

        // Iterate over the arrays
        for ($i = 0; $i < count($subjectNames); $i++) {
            // Get subject name and subject code for the current iteration
            $subjectName = $subjectNames[$i];
            $subjectCode = $subjectCodes[$i];

            // Validate inputs
            if (empty($subjectName) || empty($subjectCode)) {
                $errorMessage .= "Subject name and subject code are required. ";
            } else {
                // Bind parameters and execute the statement
                $stmt->bind_param("ss", $subjectName, $subjectCode);
                if ($stmt->execute()) {
                    // Set success flag to true if at least one subject was successfully inserted
                    $success = true;
                } else {
                    $errorMessage .= "Error inserting subject: " . $stmt->error . ". ";
                }
            }
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();

        // Prepare the success message
        if ($success) {
            $successMessage = "All subjects added successfully.";
        }

        // Redirect back with success or error messages
        $redirectUrl = $_SERVER['HTTP_REFERER'];
        if (!empty($successMessage)) {
            $redirectUrl .= "?success=" . urlencode($successMessage);
        }
        if (!empty($errorMessage)) {
            $redirectUrl .= "&error=" . urlencode($errorMessage);
        }
        header("Location: " . $redirectUrl);
        exit;
    } else {
        // Handle case where subjectName or subjectCode arrays are not set
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=Subject data not received.");
        exit;
    }
}
?>
