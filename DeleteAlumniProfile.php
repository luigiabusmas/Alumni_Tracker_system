<?php 
session_start();
require 'database.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to the login page if not logged in
    exit();
}

// Check if the ID parameter is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the delete statement
    $delete_stmt = $conn->prepare("DELETE FROM alumni_profile_table WHERE id = ?");
    $delete_stmt->bind_param("s", $id);

    // Execute the delete query
    if ($delete_stmt->execute()) {
        // Set success message and redirect
        $message = "Profile deleted successfully.";
        header("Location: AlumniProfiles.php?message=" . urlencode($message));
        exit();
    } else {
        // Set error message and redirect
        $message = "Error deleting profile.";
        header("Location: AlumniProfiles.php?message=" . urlencode($message));
        exit();
    }

    $delete_stmt->close();
} else {
    // Redirect if no ID is provided
    header("Location: AlumniProfiles.php?message=" . urlencode("No profile ID specified."));
    exit();
}
?>
