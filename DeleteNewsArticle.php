<?php
require 'session.php'; // Ensure session management is included

// Check if the article ID is provided in the URL
if (isset($_GET['id'])) {
    // Initialize database connection (Make sure $conn is defined correctly)
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    
    $article_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch the current image file to delete it if it exists
    $fetch_image_query = "SELECT image FROM newsarticle WHERE id = '$article_id'";
    $image_result = mysqli_query($conn, $fetch_image_query);

    if (!$image_result) {
        die("Error fetching image: " . mysqli_error($conn));
    }

    $image_data = mysqli_fetch_assoc($image_result);
    if ($image_data && !empty($image_data['image'])) {
        $image_path = 'image/' . $image_data['image'];
        if (file_exists($image_path)) {
            if (!unlink($image_path)) {
                echo "<script>alert('Error deleting image file.');</script>";
            }
        }
    }

    // Delete the article from the database
    $delete_query = "DELETE FROM newsarticle WHERE id = '$article_id'";
    if (mysqli_query($conn, $delete_query)) {
        // Confirm if any rows were affected
        if (mysqli_affected_rows($conn) > 0) {
            echo "<script>
                    alert('Article deleted successfully.');
                    window.location.href = 'NewsArticle.php';
                  </script>";
        } else {
            echo "<script>
                    alert('No article found with the provided ID.');
                    window.location.href = 'EditNewsArticle.php?id=$article_id';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Error deleting article: " . mysqli_error($conn) . "');
                window.location.href = 'EditNewsArticle.php?id=$article_id';
              </script>";
    }
} else {
    echo "<script>
            alert('No article ID provided.');
            window.location.href = 'NewsArticle.php';
          </script>";
}
?>
