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

// Prepare and execute the statement to fetch alumni_id and is_admin
$stmt = $conn->prepare("SELECT alumni_id, is_admin FROM users_access WHERE id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($alumni_id, $is_admin);
$stmt->fetch();
$stmt->close();

// Fetch records from the alumni_profile table using student_id
$profile_stmt = $conn->prepare("SELECT * FROM alumni_profile_table WHERE alumni_id = ?");
$profile_stmt->bind_param("s", $alumni_id); // Change to use student_id
$profile_stmt->execute();
$result = $profile_stmt->get_result();

if ($result->num_rows > 0) {
    $profile = $result->fetch_assoc(); // Fetch the profile data
} else {
    echo "No profile found for this student ID.";
    exit();
}
$profile_stmt->close();
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

<div class="content container mt-4" id="dashboard-content">
    <h1>Welcome to the Alumni Tracker Dashboard</h1>
    <p>Select a menu item to view its content.</p>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Use full jQuery -->
<script src="./resources/popper.min.js"></script>
<script src="./resources/bootstrap.min.js"></script>



</body>
</html>
