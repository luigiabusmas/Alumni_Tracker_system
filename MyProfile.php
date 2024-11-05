<?php
session_start();
require 'database.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to the login page if not logged in
    exit();
}

// Fetch user information from the database
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Prepare to fetch alumni ID and admin status
$stmt = $conn->prepare("SELECT alumni_id, is_admin FROM users_access WHERE id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($alumni_id, $is_admin);
$stmt->fetch();
$stmt->close();

// Fetch records from the alumni_profile_table based on alumni_id
$profile_stmt = $conn->prepare("SELECT * FROM alumni_profile_table WHERE alumni_id = ?");
$profile_stmt->bind_param("s", $alumni_id);
$profile_stmt->execute();
$result = $profile_stmt->get_result();

if ($result->num_rows > 0) {
    $profile = $result->fetch_assoc(); // Fetch the profile data
} else {
    echo "No profile found for this alumni ID.";
    exit();
}
$profile_stmt->close();

// Count filled and unfilled fields
$total_fields = 19; // Total number of fields to check
$filled_fields = 0;

// List of fields to check
$fields_to_check = [
    'fname', 'mname', 'lname', 'kld_email', 'home_address',
    'primary_phone', 'secondary_phone', 'gender', 'date_of_birth',
    'graduation_year', 'degree_obtained', 'employment_status',
    'company_name', 'job_title', 'job_description', 'reason_future_plans',
    'motivation_for_studies', 'degree_or_program', 'profile_picture'
];

// Calculate filled fields
foreach ($fields_to_check as $field) {
    if (!empty($profile[$field])) {
        $filled_fields++;
    }
}

// Calculate completion percentage
$completion_percentage = ($filled_fields / $total_fields) * 100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Overview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./resources/styles.css"> 
    <link rel="stylesheet" href="./resources/dashboard.css"> 

    <style>
        body {
            background-color: #f4f7fa; /* Light background color */
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px; /* Container width for a compact layout */
            margin: 0 auto;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        .progress {
            height: 20px;
            overflow: hidden;
            background-color: #e0e0e0;
            border-radius: 10px;
        }

        .progress-bar {
            font-size: 1em;
            background-color: #28a745;
            border-radius: 10px;
            position: relative;
            animation: <?= $completion_percentage < 100 ? "pulseAnimation 1.5s ease-in-out infinite" : "progressAnimation 0.6s ease forwards" ?>;
            width: <?= htmlspecialchars($completion_percentage) ?>%
        }

        @keyframes progressAnimation {
            0% { width: 0; }
            100% { width: <?= htmlspecialchars($completion_percentage) ?>% }
        }

        @keyframes pulseAnimation {
            0% { width: <?= htmlspecialchars($completion_percentage - 0) ?>%; }
            50% { width: <?= htmlspecialchars($completion_percentage + 1) ?>%; }
            100% { width: <?= htmlspecialchars($completion_percentage) ?>%; }
        }

        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            color: #fff;
        }

        .form-section {
            background-color: #fff; /* White background for sections */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Soft shadow */
            margin-bottom: 20px; /* Space between sections */
            padding: 20px; /* Padding inside each section */
        }

        .form-section h3 {
            color: #007bff; /* Bootstrap primary color for section titles */
        }

        .form-group {
            margin-bottom: 15px; /* Space between form groups */
        }

        .form-control {
            border-radius: 5px; /* Rounded corners for inputs */
            border: 1px solid #ced4da; /* Light border color */
            padding: 10px; /* Padding inside inputs */
            transition: border-color 0.3s; /* Smooth transition on focus */
        }

        .form-control:focus {
            border-color: #007bff; /* Highlight border color on focus */
            outline: none; /* Remove default outline */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Add a shadow on focus */
        }

        .text-center {
            text-align: center; /* Center text */
        }

        .img-thumbnail {
            border-radius: 10%; /* Rounded profile picture */
            max-width: 150px; /* Set max width for profile picture */
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Profile Overview</h2>

    <!-- Progress Bar -->
    <div class="progress-container">
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="<?= htmlspecialchars($completion_percentage) ?>" aria-valuemin="0" aria-valuemax="100">
                <span class="progress-text"><?= round($completion_percentage) ?>%</span>
            </div>
        </div>
        <div class="mt-2 text-center">
            <?php
            if ($completion_percentage < 50) {
                echo '<p>Keep going! You’re making progress!</p>';
            } elseif ($completion_percentage < 100) {
                echo '<p>Almost there! Just a few more fields to fill!</p>';
            } 
            ?>
        </div>
    </div>
    
    <form id="profileForm" class="container shadow p-4 rounded">
        <!-- Profile Picture -->   
         <form action="landing_page.php" method="get">
    <button type="submit" class="btn btn-success">Update Profile</button>
</form>

        <div class="form-section text-center">
            <h3>Profile Picture</h3>
            <div class="form-group">
                <img src="image/<?php echo htmlspecialchars($profile['profile_picture']); ?>" alt="Profile Picture" class="img-thumbnail">
            </div>
        </div>

        <!-- Personal Information -->
        <div class="form-section">
            
            <h3>Personal Information</h3>
            <?php 
            $personal_fields = [
                "alumni_id" => "Alumni ID", "fname" => "First Name", "mname" => "Middle Name",
                "lname" => "Last Name", "kld_email" => "Official KLD Email", "home_address" => "Home Address",
                "primary_phone" => "Primary Phone", "secondary_phone" => "Secondary Phone", "gender" => "Gender",
                "date_of_birth" => "Date of Birth"
            ];
            foreach ($personal_fields as $field => $label) {
                echo "<div class='form-group'>
                        <label>{$label}:</label>
                        <input type='text' class='form-control' value='" . htmlspecialchars($profile[$field]) . "' readonly>
                      </div>";
            }
            ?>
        </div>

        <!-- Education and Career Information -->
        <div class="form-section">
            <h3>Education and Career Information</h3>
            <?php 
            $career_fields = [
                "graduation_year" => "Graduation Year", "degree_obtained" => "Degree Obtained",
                "employment_status" => "Employment Status", "company_name" => "Company Name",
                "job_title" => "Job Title", "job_description" => "Job Description",
                "reason_future_plans" => "Reason and Future Plans", "motivation_for_studies" => "Motivation for Studies",
                "degree_or_program" => "Degree/Program Enrolled"
            ];
            foreach ($career_fields as $field => $label) {
                $value = htmlspecialchars($profile[$field]);
                if ($field == "job_description" || $field == "reason_future_plans" || $field == "motivation_for_studies") {
                    echo "<div class='form-group'>
                            <label>{$label}:</label>
                            <textarea class='form-control' readonly>{$value}</textarea>
                          </div>";
                } else {
                    echo "<div class='form-group'>
                            <label>{$label}:</label>
                            <input type='text' class='form-control' value='{$value}' readonly>
                          </div>";
                }
            }
            ?>
        </div>
    </form>
</div>
<br />
<?php include 'footer.php'; ?>

</body>
</html>
