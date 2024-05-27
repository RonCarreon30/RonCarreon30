<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if yearLevel and section are set
    if(isset($_POST['yearLevel']) && isset($_POST['section'])) {
        // Database connection
        include 'config.php';

        // Sanitize input data
        $yearLevel = is_array($_POST['yearLevel']) ? array_map('intval', $_POST['yearLevel']) : intval($_POST['yearLevel']);
        $section = is_array($_POST['section']) ? array_map('sanitize_input', $_POST['section']) : sanitize_input($_POST['section']);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO year_section (year_level, section, created_at) VALUES (?, ?, CURRENT_TIMESTAMP)");

        // Initialize variables to store errors
        $successMessage = "";
        $errorMessage = "";

        // Execute the statement
        if(is_array($yearLevel) && is_array($section)) {
            // Handle multiple entries
            if(count($yearLevel) == count($section)) {
                for($i = 0; $i < count($yearLevel); $i++) {
                    $stmt->bind_param("is", $yearLevel[$i], $section[$i]);
                    if (!$stmt->execute()) {
                        $errorMessage = "Error saving year and section: " . $stmt->error;
                        break;
                    }
                }
                if(empty($errorMessage)) {
                    $successMessage = "Year and section saved successfully.";
                }
            } else {
                $errorMessage = "Number of year levels does not match number of sections.";
            }
        } else {
            // Single entry
            $stmt->bind_param("is", $yearLevel, $section);
            if (!$stmt->execute()) {
                $errorMessage = "Error saving year and section: " . $stmt->error;
            } else {
                $successMessage = "Year and section saved successfully.";
            }
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();

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
        // Handle case where yearLevel or section are not set
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=Year level and section data not received.");
        exit;
    }
}

// Function to sanitize input data
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
