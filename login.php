<?php
session_start();
require 'database.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare a statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, userpassword, is_verified, is_active FROM users_access WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    // Check if the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashedPassword, $is_verified, $is_active);
        $stmt->fetch();

        // Check if the account is verified and active
        if ($is_verified && $is_active) {
            // Verify the password
            if (password_verify($password, $hashedPassword)) {
                // Set session variables
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;

                // Generate and set a session token
                $_SESSION['token'] = bin2hex(random_bytes(32)); // Generate a secure random token

                // Optionally, update last_login timestamp
                $updateStmt = $conn->prepare("UPDATE users_access SET last_login = NOW() WHERE id = ?");
                $updateStmt->bind_param("i", $id);
                $updateStmt->execute();

                // Redirect to the dashboard or a welcome page
                header("Location: privacy.php");
                exit();
            } else {
      
                echo "<script>
                alert('Invalid password.');
                window.location.href = 'index.php'; // Redirect to clear form after submission
              </script>";



            }
        } else {


            echo "<script>
            alert('Account is not verified or is inactive.');
            window.location.href = 'index.php'; // Redirect to clear form after submission
          </script>";
        }
    } else {

        echo "<script>
            alert('User not found.');
            window.location.href = 'index.php'; // Redirect to clear form after submission
          </script>";
        


    }
    
    $stmt->close();
}
?>