<?php  




require 'session.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to the login page if not logged in
    exit();
}

// Fetch user information from the database
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Prepare and execute the statement to fetch alumni_id and is_admin
$stmt = $conn->prepare("SELECT alumni_id, is_admin FROM users_access WHERE id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($alumni_id, $is_admin);
$stmt->fetch();
$stmt->close();

// Fetch records from the alumni_profile table using alumni_id
$profile_stmt = $conn->prepare("SELECT * FROM alumni_profile_table WHERE alumni_id = ?");
$profile_stmt->bind_param("s", $alumni_id); // Using alumni_id
$profile_stmt->execute();
$result = $profile_stmt->get_result();

if ($result->num_rows > 0) {
    $profile = $result->fetch_assoc(); // Fetch the profile data
    // Optionally handle existing profile case (e.g., show a message)
    $message = "A profile already exists for this alumni ID.";
} else {
    $profile = []; // No existing profile found
}

// Initialize variables for form data
$alumni_id = $username = $email = $fname = $lname = $kld_email = $home_address = $primary_phone = $secondary_phone = $gender = "";
$date_of_birth = $graduation_year = $degree_obtained = $employment_status = $company_name = $job_title = $job_description = $reason_future_plans = $motivation_for_studies = $degree_or_program = "";
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $alumni_id = $_POST['alumni_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $kld_email = $_POST['kld_email'];
    $home_address = $_POST['home_address'];
    $primary_phone = $_POST['primary_phone'];
    $secondary_phone = $_POST['secondary_phone'];
    $gender = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $graduation_year = $_POST['graduation_year'];
    $degree_obtained = $_POST['degree_obtained'];
    $employment_status = $_POST['employment_status'];
    $company_name = $_POST['company_name'];
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $reason_future_plans = $_POST['reason_future_plans'];
    $motivation_for_studies = $_POST['motivation_for_studies'];
    $degree_or_program = $_POST['degree_or_program'];

    // Insert new profile only if it doesn't exist
    if (empty($profile)) {
        // Prepare and execute insert statement
        $stmt = $conn->prepare("INSERT INTO alumni_profile_table 
            (alumni_id, username, email, fname, lname, kld_email, home_address, primary_phone, secondary_phone, gender, date_of_birth, graduation_year, degree_obtained, employment_status, company_name, job_title, job_description, reason_future_plans, motivation_for_studies, degree_or_program) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssssssssssssssssss", 
            $alumni_id, $username, $email, $fname, $lname, 
            $kld_email, $home_address, $primary_phone, $secondary_phone, 
            $gender, $date_of_birth, $graduation_year, $degree_obtained, 
            $employment_status, $company_name, $job_title, $job_description, 
            $reason_future_plans, $motivation_for_studies, $degree_or_program);
        
        if ($stmt->execute()) {
            $message = "Profile added successfully.";
        } else {
            $message = "Error adding profile: " . $conn->error;
        }
        $stmt->close();
    } else {
        $message = "Profile already exists for this alumni ID.";
    }
}

// Close the profile statement
$profile_stmt->close();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Alumni Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./resources/styles.css"> 
    <link rel="stylesheet" href="./resources/dashboard.css"> 
    <style>
        /* Custom styles for the form */
        .form-container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn-submit {
            padding: 10px 15px;
            color: #ffffff;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        .alert {
            color: red;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="form-container">
    <h2>Add Alumni Profile</h2>
    
    <?php if ($message): ?>
        <div class="alert"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="alumni_id">Alumni ID</label>
            <input type="text" name="alumni_id" id="alumni_id" placeholder="ex.KLDAA-202499999" required>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="fname">First Name</label>
            <input type="text" name="fname" id="fname" required>
        </div>
        <div class="form-group">
            <label for="lname">Last Name</label>
            <input type="text" name="lname" id="lname" required>
        </div>
        <div class="form-group">
            <label for="kld_email">KLD Email</label>
            <input type="email" name="kld_email" id="kld_email" required>
        </div>
        <div class="form-group">
            <label for="home_address">Home Address</label>
            <input type="text" name="home_address" id="home_address" required>
        </div>
        <div class="form-group">
            <label for="primary_phone">Primary Phone</label>
            <input type="tel" name="primary_phone" id="primary_phone" required>
        </div>
        <div class="form-group">
            <label for="secondary_phone">Secondary Phone</label>
            <input type="tel" name="secondary_phone" id="secondary_phone">
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <select name="gender" id="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" name="date_of_birth" id="date_of_birth" required>
        </div>
        <div class="form-group">
            <label for="graduation_year">Graduation Year</label>
            <input type="text" name="graduation_year" id="graduation_year" required>
        </div>
        <div class="form-group">
            <label for="degree_obtained">Degree Obtained</label>
            <input type="text" name="degree_obtained" id="degree_obtained" required>
        </div>
        <div class="form-group">
            <label for="employment_status">Employment Status</label>
            <select name="employment_status" id="employment_status" required>
                <option value="Unemployed">Unemployed</option>
                <option value="Employed">Employed</option>
                <option value="Pursued Studies">Pursued Studies</option>
            </select>
        </div>
        <div class="form-group">
            <label for="company_name">Company Name</label>
            <input type="text" name="company_name" id="company_name">
        </div>
        <div class="form-group">
            <label for="job_title">Job Title</label>
            <input type="text" name="job_title" id="job_title">
        </div>
        <div class="form-group">
            <label for="job_description">Job Description</label>
            <textarea name="job_description" id="job_description"></textarea>
        </div>
        <div class="form-group">
            <label for="reason_future_plans">Reason for Future Plans</label>
            <textarea name="reason_future_plans" id="reason_future_plans"></textarea>
        </div>
        <div class="form-group">
            <label for="motivation_for_studies">Motivation for Studies</label>
            <textarea name="motivation_for_studies" id="motivation_for_studies"></textarea>
        </div>
        <div class="form-group">
            <label for="degree_or_program">Degree or Program</label>
            <input type="text" name="degree_or_program" id="degree_or_program">
        </div>
        <div class="form-group">
            <button type="submit" class="btn-submit">Add Profile</button>
        </div>
    </form>
</div>
<?php include 'footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>
</body>
</html>
