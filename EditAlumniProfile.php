<?php 
session_start();
require 'database.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to the login page if not logged in
    exit();
}

if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    echo "<script>
            alert('$message');
            window.location.href = 'EditAlumniProfile.php?id=" . urlencode($_GET['id']) . "';
          </script>";
    exit; // Prevent further script execution
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
$profile_stmt = $conn->prepare("SELECT * FROM alumni_profile_table WHERE id = ?");
$profile_stmt->bind_param("s", $_GET['id']);
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

// Handle form submission
$message = ''; // Variable to hold success or error messages
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $updatedData = [
        'alumni_id' => $_POST['alumni_id'],
        'fname' => $_POST['fname'],
        'mname' => $_POST['mname'],
        'lname' => $_POST['lname'],
        'kld_email' => $_POST['kld_email'],
        'home_address' => $_POST['home_address'],
        'primary_phone' => $_POST['primary_phone'],
        'secondary_phone' => $_POST['secondary_phone'],
        'gender' => $_POST['gender'],
        'date_of_birth' => $_POST['date_of_birth'],
        'graduation_year' => $_POST['graduation_year'],
        'degree_obtained' => $_POST['degree_obtained'],
        'employment_status' => $_POST['employment_status'],
        'company_name' => $_POST['company_name'],
        'job_title' => $_POST['job_title'],
        'job_description' => $_POST['job_description'],
        'reason_future_plans' => $_POST['reason_future_plans'],
        'motivation_for_studies' => $_POST['motivation_for_studies'],
        'degree_or_program' => $_POST['degree_or_program'],
        // Check if a new profile picture has been uploaded
        'profile_picture' => !empty($_FILES['profile_picture']['name']) ? $_FILES['profile_picture']['name'] : $profile['profile_picture']
    ];

    // Check if any values have changed
    $changes = false;
    foreach ($updatedData as $key => $value) {
        if ($value !== $profile[$key] && $key !== 'profile_picture') {
            $changes = true;
            break;
        }
    }

    // Only proceed with update if there are changes
    if ($changes || !empty($_FILES['profile_picture']['name'])) {
        // Prepare update query
        $update_stmt = $conn->prepare("UPDATE alumni_profile_table SET 
            fname=?, 
            mname=?, 
            lname=?, 
            kld_email=?, 
            home_address=?, 
            primary_phone=?, 
            secondary_phone=?, 
            gender=?, 
            date_of_birth=?, 
            graduation_year=?, 
            degree_obtained=?, 
            employment_status=?, 
            company_name=?, 
            job_title=?, 
            job_description=?, 
            reason_future_plans=?, 
            motivation_for_studies=?, 
            degree_or_program=?, 
            profile_picture=? 
            WHERE id=?");
        
        $update_stmt->bind_param("ssssssssssssssssssss", 
            $updatedData['fname'], $updatedData['mname'], $updatedData['lname'], 
            $updatedData['kld_email'], $updatedData['home_address'], 
            $updatedData['primary_phone'], $updatedData['secondary_phone'], 
            $updatedData['gender'], $updatedData['date_of_birth'], 
            $updatedData['graduation_year'], $updatedData['degree_obtained'], 
            $updatedData['employment_status'], $updatedData['company_name'], 
            $updatedData['job_title'], $updatedData['job_description'], 
            $updatedData['reason_future_plans'], $updatedData['motivation_for_studies'], 
            $updatedData['degree_or_program'], $updatedData['profile_picture'], 
            $updatedData[$_GET['id']]
        );

 // Execute the update query
 if ($update_stmt->execute()) {
    // Handle file upload if profile picture is updated
    if (!empty($_FILES['profile_picture']['name'])) {
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], "image/" . $updatedData['profile_picture']);
    }
    $message = 'Profile updated successfully.'; // Set success message
    header("Location: EditAlumniProfile.php?id=" . urlencode($_GET['id']) . "&message=" . urlencode($message));
    // Redirect to the same page with the message
    exit();
} else {
    $message = 'Error updating profile.'; // Set error message
    exit();
}
} else {
$message = 'No changes were made to the profile.'; // No changes message
exit();
}
}
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

<script>
let originalValues = {};

function makeFormEditable() {
    // Store original values
    document.querySelectorAll("#profileForm .form-control").forEach((input) => {
        originalValues[input.name] = input.value; // Save original value
        input.removeAttribute("readonly");        // Make editable
    });

    // Show the Cancel and Save buttons, hide Edit button
    document.getElementById("editButton").style.display = "none";
    document.getElementById("cancelButton").style.display = "inline-block";
    document.getElementById("saveButton").style.display = "inline-block";
    document.getElementById("imageupload").style.display = "inline-block";

 // Enable the dropdowns (remove 'disabled' attribute)
 document.querySelectorAll("#profileForm select").forEach((select) => {
        select.removeAttribute("disabled"); // Make dropdowns editable
    });

}

function cancelEdit() {
    // Restore original values
    document.querySelectorAll("#profileForm .form-control").forEach((input) => {
        
        if (input.name in originalValues) {
            input.value = originalValues[input.name]; // Reset to original value
        }
        input.setAttribute("readonly", "true"); // Make read-only again
    });
    // Disable dropdowns again to make them read-only
    document.querySelectorAll("#profileForm select").forEach((select) => {
        select.setAttribute("disabled", "true");
    });
    // Hide the Cancel and Save buttons, show Edit button
    document.getElementById("editButton").style.display = "inline-block";
    document.getElementById("cancelButton").style.display = "none";
    document.getElementById("saveButton").style.display = "none";
    document.getElementById("imageupload").style.display = "none";
}

function submitForm() {
    // Programmatically submit the form
    document.getElementById("profileForm").submit();
}
</script>

</head>
<body>

<?php include 'header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center">Profile Overview</h2>
    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
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

    <!-- Update Profile Button -->
 
          
    <div class="form-section d-flex justify-content-between">
    <div class="text-left">
        <a href="AlumniProfiles.php" class="btn btn-success">Back to list</a>
        <button class="btn btn-primary" onclick="makeFormEditable()" id="editButton">Edit Profile</button>
        <button type="button" id="cancelButton" class="btn btn-primary" style="display:none;" onclick="cancelEdit()">Cancel</button>
        <!-- Save button triggers form submission programmatically -->
        <button type="button" id="saveButton" class="btn btn-secondary" style="display:none;" onclick="submitForm()">Save Changes</button>
    </div>
    <div class="text-right">
    <a href="javascript:void(0);" onclick="confirmDelete()" class="btn btn-danger">Delete</a>
</div>
<script>
function confirmDelete() {
    const confirmDelete = confirm("Are you sure you want to delete this profile?");
    if (confirmDelete) {
        window.location.href = "DeleteAlumniProfile.php?id=<?php echo urlencode($profile['id']); ?>"; // Redirect to delete script
    }
}
</script>
</div>
    <!-- Profile Form -->
<!-- Profile Form -->
<form action="EditAlumniProfile.php?id=<?php echo urlencode($_GET['id']); ?>" method="POST" enctype="multipart/form-data" id="profileForm" class="container shadow p-4 rounded">

    <input type="hidden" name="alumni_id" value="<?= htmlspecialchars($alumni_id) ?>">

    <!-- Profile Picture -->
    <div class="form-section text-center">
        <h3>Profile Picture</h3>
        <div class="form-group">
            <img src="image/<?= htmlspecialchars($profile['profile_picture']); ?>" alt="Profile Picture" class="img-thumbnail">
        </div>
        <div class="form-group">
            <label>Change Profile Picture:</label>
            <input type="file" name="profile_picture" id="imageupload" value='<?= htmlspecialchars($profile['profile_picture']); ?>' class="form-control" style="display:none">
        </div>
 
</div>
    <!-- Personal Information -->
    <div class="form-section">
        <h3>Personal Information</h3>
        <div class='form-group'>
            <label>First Name:</label>
            <input type='text' name='fname' class='form-control' value='<?= htmlspecialchars($profile["fname"]) ?>' readonly>
        </div>
        <div class='form-group'>
            <label>Middle Name:</label>
            <input type='text' name='mname' class='form-control' value='<?= htmlspecialchars($profile["mname"]) ?>' readonly>
        </div>
        <div class='form-group'>
            <label>Last Name:</label>
            <input type='text' name='lname' class='form-control' value='<?= htmlspecialchars($profile["lname"]) ?>' readonly>
        </div>
        <div class='form-group'>
            <label>Official KLD Email:</label>
            <input type='text' name='kld_email' class='form-control' value='<?= htmlspecialchars($profile["kld_email"]) ?>' readonly>
        </div>
        <div class='form-group'>
            <label>Home Address:</label>
            <input type='text' name='home_address' class='form-control' value='<?= htmlspecialchars($profile["home_address"]) ?>' readonly>
        </div>
        <div class='form-group'>
            <label>Primary Phone:</label>
            <input type='text' name='primary_phone' class='form-control' value='<?= htmlspecialchars($profile["primary_phone"]) ?>' readonly>
        </div>
        <div class='form-group'>
            <label>Secondary Phone:</label>
            <input type='text' name='secondary_phone' class='form-control' value='<?= htmlspecialchars($profile["secondary_phone"]) ?>' readonly>
        </div>
        <div class='form-group'>
            <label>Gender:</label>
            <select name='gender' class='form-control' <?= isset($profile["gender"]) ? 'disabled' : '' ?>>
                <option value='Male' <?= $profile["gender"] === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value='Female' <?= $profile["gender"] === 'Female' ? 'selected' : '' ?>>Female</option>
                <option value='Other' <?= $profile["gender"] === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class='form-group'>
            <label>Date of Birth:</label>
            <input type='date' name='date_of_birth' class='form-control' value='<?= htmlspecialchars($profile["date_of_birth"]) ?>' readonly>
        </div>
    </div>

    <!-- Education and Career Information -->
    <div class="form-section">
        <h3>Education and Career Information</h3>
        <div class='form-group'>
            <label>Graduation Year:</label>
            <input type='text' name='graduation_year' class='form-control' value='<?= htmlspecialchars($profile["graduation_year"]) ?>' readonly>
        </div>
        <div class='form-group'>
            <label>Degree Obtained:</label>
            <input type='text' name='degree_obtained' class='form-control' value='<?= htmlspecialchars($profile["degree_obtained"]) ?>' readonly>
        </div>
        <div class='form-group'>
            <label>Employment Status:</label>
            <select name='employment_status' class='form-control' <?= isset($profile["employment_status"]) ? 'disabled' : '' ?>>
    <option value='Unemployed' <?= $profile["employment_status"] === 'Unemployed' ? 'selected' : '' ?>>Unemployed</option>
    <option value='Employed' <?= $profile["employment_status"] === 'Employed' ? 'selected' : '' ?>>Employed</option>
    <option value='Pursued Studies' <?= $profile["employment_status"] === 'Pursued Studies' ? 'selected' : '' ?>>Pursued Studies</option>
</select>




            
        </div>
        <div class='form-group'>
            <label>Company Name:</label>
            <input type='text' name='company_name' class='form-control' value='<?= htmlspecialchars($profile["company_name"]) ?>' readonly>
        </div>
        <div class='form-group'>
            <label>Job Title:</label>
            <input type='text' name='job_title' class='form-control' value='<?= htmlspecialchars($profile["job_title"]) ?>' readonly>
        </div>
        <div class='form-group'>
            <label>Job Description:</label>
            <textarea name='job_description' class='form-control' readonly><?= htmlspecialchars($profile["job_description"]) ?></textarea>
        </div>
        <div class='form-group'>
            <label>Reason and Future Plans:</label>
            <textarea name='reason_future_plans' class='form-control' readonly><?= htmlspecialchars($profile["reason_future_plans"]) ?></textarea>
        </div>
        <div class='form-group'>
            <label>Motivation for Studies:</label>
            <textarea name='motivation_for_studies' class='form-control' readonly><?= htmlspecialchars($profile["motivation_for_studies"]) ?></textarea>
        </div>
        <div class='form-group'>
            <label>Degree/Program Enrolled:</label>
            <input type='text' name='degree_or_program' class='form-control' value='<?= htmlspecialchars($profile["degree_or_program"]) ?>' readonly>
        </div>
    </div>

    <div class="text-left">
        <button type="submit" id="saveButton" class="btn btn-success" style="display:none;">Save Changes</button>
    </div>
    </div>
</form>

</div>
<br />
</div>

</body>
<?php include 'footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Use full jQuery -->
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>

</html>