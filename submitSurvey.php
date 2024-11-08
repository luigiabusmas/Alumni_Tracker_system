<?php
require 'session.php';
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the POST data
    $alumni_id = isset($_POST['alumni_id']) ? $_POST['alumni_id'] : '';  // Get the alumni ID from the form
    $task_efficiency = isset($_POST['task_efficiency']) ? $_POST['task_efficiency'] : 'Not rated';
    $ui_satisfaction = isset($_POST['ui_satisfaction']) ? $_POST['ui_satisfaction'] : 'Not rated';
    $system_stability = isset($_POST['system_stability']) ? $_POST['system_stability'] : 'Not rated';
    $maintainability_troubleshooting = isset($_POST['maintainability_troubleshooting']) ? $_POST['maintainability_troubleshooting'] : 'Not rated';

    // Check if the alumni_id exists in the user_access table
    $check_alumni_query = "SELECT * FROM users_access WHERE alumni_id = ?";
    $stmt = $conn->prepare($check_alumni_query);
    $stmt->bind_param("s", $alumni_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If alumni_id does not exist, show an error
    if ($result->num_rows == 0) {
        echo "<p>Error: Invalid Alumni ID. Please provide a valid alumni ID.</p>";
        exit; // Stop further processing
    }

    // Insert the data into the feedback table
    $stmt = $conn->prepare("INSERT INTO feedback 
                            (alumni_id, 
                            task_efficiency, 
                            ui_satisfaction, 
                            system_stability, 
                             maintainability_troubleshooting , 
                             survey_taken_at) 
                            VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
    $stmt->bind_param("sssss", $alumni_id, $task_efficiency, $ui_satisfaction, $system_stability,
                      $maintainability_troubleshooting);
    $stmt->execute();

    // // Check if the data was successfully inserted
    // if ($stmt->affected_rows > 0) {
    //     echo "<h3>Survey Submitted Successfully!</h3>";
    //     echo "<p><strong>Alumni ID:</strong> " . htmlspecialchars($alumni_id) . "</p>";
    //     echo "<p><strong>Task Efficiency:</strong> " . getRatingText($task_efficiency) . "</p>";
    //     echo "<p><strong>User Interface Satisfaction:</strong> " . getRatingText($ui_satisfaction) . "</p>";
    //     echo "<p><strong>System Stability:</strong> " . getRatingText($system_stability) . "</p>";
    //     echo "<p><strong>Troubleshooting Experience:</strong> " . getRatingText($maintainability_troubleshooting) . "</p>";

    // } else {
    //     echo "<p>Error: Could not save the feedback. Please try again.</p>";
    // }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
header("Location: logout.php");
// Function to convert rating value to text (based on the value received from radio buttons)

?>
