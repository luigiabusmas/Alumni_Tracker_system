<?php
require 'session.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $thread_id = $_GET['id'];

    // Ensure the thread exists before attempting to delete
    $query = "SELECT * FROM forums WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $thread_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            // Prepare deletion query
            $delete_query = "DELETE FROM forums WHERE id = ?";
            if ($delete_stmt = $conn->prepare($delete_query)) {
                $delete_stmt->bind_param("i", $thread_id);
                $delete_stmt->execute();

                // Redirect to a confirmation page or back to the thread list after deletion
                echo "<script>alert('Thread deleted successfully.'); window.location.href='Forums.php';</script>";
            } else {
                echo "<script>alert('Error deleting thread.'); window.location.href='EditForums.php?id=thread_id';</script>";
            }
        } else {
            echo "<script>alert('Thread not found.'); window.location.href='Forums.php';</script>";
        }

        $stmt->close();
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='Forums.php';</script>";
}

$conn->close();
?>
