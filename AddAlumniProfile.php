<?php 
require 'session.php'; 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input data
    $alumni_id = mysqli_real_escape_string($conn, $_POST['alumni_id']);
    $username = $alumni_id; // Automatically set the username based on alumni_id
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $mname = mysqli_real_escape_string($conn, $_POST['mname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $kld_email = mysqli_real_escape_string($conn, $_POST['kld_email']);
    $home_address = mysqli_real_escape_string($conn, $_POST['home_address']);
    $primary_phone = mysqli_real_escape_string($conn, $_POST['primary_phone']);
    $secondary_phone = mysqli_real_escape_string($conn, $_POST['secondary_phone']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
    $graduation_year = mysqli_real_escape_string($conn, $_POST['graduation_year']);
    $degree_obtained = mysqli_real_escape_string($conn, $_POST['degree_obtained']);
    $employment_status = mysqli_real_escape_string($conn, $_POST['employment_status']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $job_title = mysqli_real_escape_string($conn, $_POST['job_title']);
    $job_description = mysqli_real_escape_string($conn, $_POST['job_description']);
    $reason_future_plans = mysqli_real_escape_string($conn, $_POST['reason_future_plans']);
    $motivation_for_studies = mysqli_real_escape_string($conn, $_POST['motivation_for_studies']);
    $degree_or_program = mysqli_real_escape_string($conn, $_POST['degree_or_program']);
    
    // Handle file upload for profile picture
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        // Define allowed file types and max size
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        // Get file info
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_size = $_FILES['profile_picture']['size'];
        $file_type = $_FILES['profile_picture']['type'];
        
        // Check file type and size
        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            // Generate a unique name for the file
            $unique_file_name = uniqid() . '_' . basename($file_name);
            $destination = 'image/' . $unique_file_name;

            if (move_uploaded_file($file_tmp, $destination)) {
                // Save only the filename to the database, not the path
                $profile_picture = $unique_file_name;
            } else {
                echo "Failed to upload file.";
            }
        } else {
            echo "Invalid file type or size. Please upload a valid image (JPG, PNG, GIF) under 5MB.";
        }
    }
    
    // Insert data into the database
    $sql = "INSERT INTO alumni_profile_table (username, email, alumni_id, fname, mname, lname, kld_email, home_address, primary_phone, secondary_phone, gender, date_of_birth, graduation_year, degree_obtained, employment_status, company_name, job_title, job_description, reason_future_plans, motivation_for_studies, degree_or_program, profile_picture) 
            VALUES ('$username', '$email', '$alumni_id', '$fname', '$mname', '$lname', '$kld_email', '$home_address', '$primary_phone', '$secondary_phone', '$gender', '$date_of_birth', '$graduation_year', '$degree_obtained', '$employment_status', '$company_name', '$job_title', '$job_description', '$reason_future_plans', '$motivation_for_studies', '$degree_or_program', '$profile_picture')";

    if (mysqli_query($conn, $sql)) {
        echo "New alumni profile created successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./resources/styles.css"> 
    <link rel="stylesheet" href="./resources/dashboard.css"> 

</head>
<body>

<?php include 'header.php'; ?>

<form action="AddAlumniProfile.php" method="POST" enctype="multipart/form-data">
    <div class="container">
        <h2>Create Alumni Profile</h2>


        <div class="form-group">
            <label for="email">Primary Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="alumni_id">Alumni ID:</label>
            <input type="text" class="form-control" id="alumni_id" name="alumni_id" required>
        </div>

        <div class="form-group">
            <label for="fname">First Name:</label>
            <input type="text" class="form-control" id="fname" name="fname" required>
        </div>

        <div class="form-group">
            <label for="mname">Middle Name:</label>
            <input type="text" class="form-control" id="mname" name="mname">
        </div>

        <div class="form-group">
            <label for="lname">Last Name:</label>
            <input type="text" class="form-control" id="lname" name="lname" required>
        </div>

        <div class="form-group">
            <label for="kld_email">KLD Email:</label>
            <input type="email" class="form-control" id="kld_email" name="kld_email" required>
        </div>

        <div class="form-group">
            <label for="home_address">Home Address:</label>
            <textarea class="form-control" id="home_address" name="home_address"></textarea>
        </div>

        <div class="form-group">
            <label for="primary_phone">Primary Phone:</label>
            <input type="text" class="form-control" id="primary_phone" name="primary_phone">
        </div>

        <div class="form-group">
            <label for="secondary_phone">Secondary Phone:</label>
            <input type="text" class="form-control" id="secondary_phone" name="secondary_phone">
        </div>

        <div class="form-group">
            <label for="gender">Gender:</label>
            <select class="form-control" id="gender" name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
        </div>

        <div class="form-group">
            <label for="graduation_year">Graduation Year:</label>
            <input type="number" class="form-control" id="graduation_year" name="graduation_year" min="1900" max="2099">
        </div>

        <div class="form-group">
            <label for="degree_obtained">Degree Obtained:</label>
            <input type="text" class="form-control" id="degree_obtained" name="degree_obtained">
        </div>

        <div class="form-group">
            <label for="employment_status">Employment Status:</label>
            <select class="form-control" id="employment_status" name="employment_status">
                <option value="Unemployed">Unemployed</option>
                <option value="Employed">Employed</option>
                <option value="Pursued Studies">Pursued Studies</option>
            </select>
        </div>

        <div class="form-group">
            <label for="company_name">Company Name:</label>
            <input type="text" class="form-control" id="company_name" name="company_name">
        </div>

        <div class="form-group">
            <label for="job_title">Job Title:</label>
            <input type="text" class="form-control" id="job_title" name="job_title">
        </div>

        <div class="form-group">
            <label for="job_description">Job Description:</label>
            <textarea class="form-control" id="job_description" name="job_description"></textarea>
        </div>

        <div class="form-group">
            <label for="reason_future_plans">Reason for Current Status & Future Plans:</label>
            <textarea class="form-control" id="reason_future_plans" name="reason_future_plans"></textarea>
        </div>

        <div class="form-group">
            <label for="motivation_for_studies">Motivation for Further Studies:</label>
            <textarea class="form-control" id="motivation_for_studies" name="motivation_for_studies"></textarea>
        </div>

        <div class="form-group">
            <label for="degree_or_program">Further Studies Degree or Program:</label>
            <input type="text" class="form-control" id="degree_or_program" name="degree_or_program">
        </div>

        <div class="form-group">
            <label for="profile_picture">Profile Picture:</label>
            <input type="file" class="form-control" id="profile_picture" name="profile_picture">
        </div>

        <div class="form-group text-left">
            <button type="submit" class="btn btn-primary">Save Profile</button>
            <a href="alumniDashboard.php" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</form>


<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Use full jQuery -->
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>



</body>
</html>
