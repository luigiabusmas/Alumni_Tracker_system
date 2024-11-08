<?php
require 'session.php';



// Assume you have the alumni_id or user_id available from the session or database


// Query to get the survey_taken_at date for the user (adjust according to your table and field names)
$query = "SELECT survey_taken_at FROM feedback WHERE alumni_id = '$alumni_id' ORDER BY survey_taken_at DESC LIMIT 1";
$result = mysqli_query($conn, $query);

// Check if the result is not empty
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $survey_taken_at = $row['survey_taken_at'];

    $current_date = new DateTime(); // Get the current date and time
    $survey_date = new DateTime($survey_taken_at); // The survey taken date
    
    // Calculate the difference between the two dates
    $interval = $current_date->diff($survey_date);
    
    // Get the difference in days
    $days_difference = $interval->days; // This will give you the absolute difference in days
    
    // Example usage
    echo "The difference in days is: " . $days_difference;

    if ($days_difference < 7) {
        header("Location: logout.php");
     echo $current_date;
    } 
} 

?>
<!-- Include Bootstrap and Font Awesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />

<!-- Start of the main container (container-fluid to make full-width) -->
<div class="container-fluid mt-5">
    <form action="submitSurvey.php" method="POST">
        <h3 class="mb-4 text-center">Survey Feedback</h3>

        <!-- Alumni ID Section -->
        <div class="container mb-4 border p-3">
            <div class="row mb-3">
                <div class="col-md-6 offset-md-3">
                    <label for="alumni_id" class="form-label"><i class="fas fa-user"></i> Alumni ID</label>
                    <p class="text-muted">Your Alumni ID is used to track your feedback and ensure the data is linked to your profile.</p>
                    <input type="hidden" id="alumni_id" value="<?php echo $alumni_id; ?>" name="alumni_id" required>
                </div>
            </div>
        </div>

        <!-- Functionality Section -->
        <div class="container mb-4 border p-3">
            <h4 class="mb-3"><i class="fas fa-cogs"></i> 4.1 Functionality</h4>
            <p class="text-muted">Please rate the overall functionality of the system based on your experience. Consider aspects like efficiency, speed, and ease of use.</p>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="task_efficiency" class="form-label"><i class="fas fa-tasks"></i> Task Efficiency:</label>
                    <p class="text-muted">How efficiently could you complete the tasks in the system? Select one of the following options based on your experience:</p>
                    <div class="d-flex flex-wrap">
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="task_efficiency" value="1">
                            <label class="form-check-label">Poor</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="task_efficiency" value="2">
                            <label class="form-check-label">Fair</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="task_efficiency" value="3">
                            <label class="form-check-label">Good</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="task_efficiency" value="4">
                            <label class="form-check-label">Very Good</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="task_efficiency" value="5">
                            <label class="form-check-label">Excellent</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usability Section -->
        <div class="container mb-4 border p-3">
            <h4 class="mb-3"><i class="fas fa-desktop"></i> 4.2 Usability</h4>
            <p class="text-muted">Evaluate the ease of use of the interface. Consider how intuitive the layout, navigation, and design are for performing your tasks.</p>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="ui_satisfaction" class="form-label"><i class="fas fa-smile"></i> User Interface Satisfaction:</label>
                    <p class="text-muted">How satisfied were you with the overall user interface design? Consider factors like clarity, accessibility, and aesthetic appeal.</p>
                    <div class="d-flex flex-wrap">
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="ui_satisfaction" value="1">
                            <label class="form-check-label">Poor</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="ui_satisfaction" value="2">
                            <label class="form-check-label">Fair</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="ui_satisfaction" value="3">
                            <label class="form-check-label">Good</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="ui_satisfaction" value="4">
                            <label class="form-check-label">Very Good</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="ui_satisfaction" value="5">
                            <label class="form-check-label">Excellent</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="system_stability" class="form-label"><i class="fas fa-shield-alt"></i> System Stability:</label>
                    <p class="text-muted">Did you encounter any system issues such as downtime, errors, or crashes? Rate the stability of the system during your interaction.</p>
                    <div class="d-flex flex-wrap">
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="system_stability" value="1">
                            <label class="form-check-label">Poor</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="system_stability" value="2">
                            <label class="form-check-label">Fair</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="system_stability" value="3">
                            <label class="form-check-label">Good</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="system_stability" value="4">
                            <label class="form-check-label">Very Good</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="system_stability" value="5">
                            <label class="form-check-label">Excellent</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintainability Section -->
        <div class="container mb-4 border p-3">
            <h4 class="mb-3"><i class="fas fa-tools"></i> 4.3 Maintainability</h4>
            <p class="text-muted">Please rate the ease of maintenance and troubleshooting. Were issues resolved quickly, and were there recurring problems during your use of the system?</p>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="maintainability_troubleshooting" class="form-label"><i class="fas fa-wrench"></i> Troubleshooting Experience:</label>
                    <p class="text-muted">How easy was it to resolve any issues you encountered? Rate your experience based on the speed and effectiveness of the troubleshooting process.</p>
                    <div class="d-flex flex-wrap">
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="maintainability_troubleshooting" value="1">
                            <label class="form-check-label">Poor</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="maintainability_troubleshooting" value="2">
                            <label class="form-check-label">Fair</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="maintainability_troubleshooting" value="3">
                            <label class="form-check-label">Good</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="maintainability_troubleshooting" value="4">
                            <label class="form-check-label">Very Good</label>
                        </div>
                        <div class="form-check form-check-inline w-100">
                            <input class="form-check-input" type="radio" name="maintainability_troubleshooting" value="5">
                            <label class="form-check-label">Excellent</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="container mb-4">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <button type="submit" class="btn btn-success w-100">Submit Feedback</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Include Bootstrap JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
