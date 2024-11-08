<?php
require 'session.php'; // Ensure session management is included


// Check if the events ID is provided in the URL
if (isset($_GET['id'])) {
    $events_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch the current image file to delete it if it exists
    $fetch_image_query = "SELECT image FROM events WHERE id = '$events_id'";
    $image_result = mysqli_query($conn, $fetch_image_query);
    $image_data = mysqli_fetch_assoc($image_result);
    
    if ($image_data && !empty($image_data['image'])) {
        $image_path = 'image/' . $image_data['image'];
        if (file_exists($image_path)) {
            unlink($image_path); // Delete the image file
        }
    }

    // Delete the events from the database
    $delete_query = "DELETE FROM events WHERE id = '$events_id'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>
                alert('Event deleted successfully.');
                window.location.href = 'Events.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting events.');
                window.location.href = 'EditEvents.php?id=$events_id';
              </script>";
    }
} else {
    echo "<script>
            alert('No events ID provided.');
            window.location.href = 'Events.php';
          </script>";
}
?>
