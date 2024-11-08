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
$stmt = $conn->prepare("SELECT alumni_id, is_admin,alumni_id FROM users_access WHERE id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($alumni_id, $is_admin,$alumni_id);
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