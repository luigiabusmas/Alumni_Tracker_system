<?php
require 'session.php'; // Include your database connection

if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    echo "<script>
            alert('$message');
            window.location.href = 'AddAlumniProfile.php';
          </script>";
    exit; // Prevent further script execution
}

// Fetch profile data based on alumni_id from GET request


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
            WHERE alumni_id=?");
        
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
            $updatedData['alumni_id']
        );

        // Execute the update query
        if ($update_stmt->execute()) {
            // Handle file upload if profile picture is updated
            if (!empty($_FILES['profile_picture']['name'])) {
                move_uploaded_file($_FILES['profile_picture']['tmp_name'], "image/" . $updatedData['profile_picture']);
            }
            $message = 'Profile updated successfully.'; // Set success message
            header("Location: AlumniProfiles.php?message=" . urlencode($message)); // Redirect to the same page with the message
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
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./resources/styles.css"> 
    <link rel="stylesheet" href="./resources/dashboard.css"> 
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7fa; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .form-section { background-color: #fff; border-radius: 10px; padding: 20px; margin-bottom: 20px; }
        .form-control { border-radius: 5px; padding: 10px; border: 1px solid #ced4da; }
        .img-thumbnail { max-width: 150px; border-radius: 10%; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center">Edit Profile</h2>

        <form action="AddAlumniProfile.php" method="POST" enctype="multipart/form-data" id="profileForm" class="container shadow p-4 rounded">

<!-- Profile Picture -->
<div class="form-section text-center">
    <h3>Profile Picture</h3>
    <div class="form-group">
        <label>Upload Profile Picture:</label>
        <input type="file" name="profile_picture" class="form-control" required>
    </div>
</div>

<!-- Personal Information -->
<div class="form-section">
    <h3>Personal Information</h3>
    <div class="form-group">
        <label>First Name:</label>
        <input type="text" name="fname" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Middle Name:</label>
        <input type="text" name="mname" class="form-control">
    </div>
    <div class="form-group">
        <label>Last Name:</label>
        <input type="text" name="lname" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="kld_email" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Home Address:</label>
        <input type="text" name="home_address" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Primary Phone:</label>
        <input type="text" name="primary_phone" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Secondary Phone:</label>
        <input type="text" name="secondary_phone" class="form-control">
    </div>
    <div class="form-group">
        <label>Gender:</label>
        <select name="gender" class="form-control" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
    </div>
    <div class="form-group">
        <label>Date of Birth:</label>
        <input type="date" name="date_of_birth" class="form-control" required>
    </div>
</div>

<!-- Academic and Employment Information -->
<div class="form-section">
    <h3>Academic and Employment Information</h3>
    <div class="form-group">
        <label>Graduation Year:</label>
        <input type="text" name="graduation_year" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Degree Obtained:</label>
        <input type="text" name="degree_obtained" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Employment Status:</label>
        <select name="employment_status" class="form-control" required>
            <option value="Unemployed">Unemployed</option>
            <option value="Employed">Employed</option>
            <option value="Pursued Studies">Pursued Studies</option>
        </select>
    </div>
    <div class="form-group">
        <label>Company Name:</label>
        <input type="text" name="company_name" class="form-control">
    </div>
    <div class="form-group">
        <label>Job Title:</label>
        <input type="text" name="job_title" class="form-control">
    </div>
    <div class="form-group">
        <label>Job Description:</label>
        <textarea name="job_description" class="form-control" rows="5"></textarea>
    </div>
</div>

<div class="form-group text-right">
    <button type="submit" class="btn btn-primary">Save Profile</button>
    <a href="AlumniProfiles.php" class="btn btn-secondary">Cancel</a>
</div>
</form>

    </div>
</body>
</html>
