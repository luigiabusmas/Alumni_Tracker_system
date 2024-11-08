<?php
require 'session.php'; // Ensure session management is included

// Check if the job listing ID is provided in the URL
if (isset($_GET['id'])) {
    $job_id = mysqli_real_escape_string($conn, $_GET['id']);



    // Delete the job listing from the database
    $delete_query = "DELETE FROM job_listings WHERE id = '$job_id'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>
                alert('Job listing deleted successfully.');
                window.location.href = 'JobListing.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting job listing.');
                window.location.href = 'EditJobListing.php?id=$job_id';
              </script>";
    }
} else {
    echo "<script>
            alert('No job listing ID provided.');
            window.location.href = 'JobListing.php';
          </script>";
}
?>
