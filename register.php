<?php 
session_start();
require 'database.php'; // Include your database connection

// Function to handle error messages
function handleError($message) {
    echo "<div style='color: red;'><strong>Error:</strong> " . htmlspecialchars($message) . "</div>";
    echo "<br/><a href=\"index.php\">Back To login page</a>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $alumni_id = trim($_POST['alumni_id']);

    // Check if the alumni ID exists in the alumni_profile_table
    $stmt = $conn->prepare("SELECT * FROM alumni_profile_table WHERE alumni_id = ?");
    if (!$stmt) {
        handleError("Database query error: " . htmlspecialchars($conn->error));
        exit();
    }
    
    $stmt->bind_param("s", $alumni_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If a matching record is found
    if ($result->num_rows > 0) {
        // Fetch the user's details
        $user_data = $result->fetch_assoc();

        // Use a default password
        $default_password = "test"; // Default password
        // Hash the password
        $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);

        // Use primary_email as username
        $username = $user_data['alumni_id']; 

        // Check if the username already exists in users_access table
        $stmt = $conn->prepare("SELECT * FROM users_access WHERE username = ?");
        if (!$stmt) {
            handleError("Database query error: " . htmlspecialchars($conn->error));
            exit();
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Username already exists
            handleError("The email address is already registered. Please use a different email or login.");
        } else {
            // Insert the new user into the users_access table
            $stmt = $conn->prepare("INSERT INTO users_access (username, email, userpassword, alumni_id) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                handleError("Failed to prepare statement: " . htmlspecialchars($conn->error));
                exit();
            }

            $stmt->bind_param("ssss", $username, $user_data['email'], $hashed_password, $alumni_id);

            if ($stmt->execute()) {
                // Optionally, you can send the default password to the user's email
                // mail($user_data['email'], "Your Registration", "Your password is: " . $default_password);
                echo "<div style='color: green;'><strong>Success:</strong> Registration successful. Your login credentials have been created.</div>";
                echo "<br/><a href=\"index.php\">Back To login page</a>";
            } else {
                handleError("Execution failed: " . htmlspecialchars($stmt->error));
            }
        }
        $stmt->close();
    } else {
        handleError("Student ID not found."); // Use custom error handler
    }
}
?>
