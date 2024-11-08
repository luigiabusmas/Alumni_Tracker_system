<?php
// Include session and handle logout logic
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Successful</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome (optional, for icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <!-- Custom Styles for Notification -->
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        
        .notification-container {
            background-color: #28a745;
            color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .notification-container h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .notification-container p {
            font-size: 1.1rem;
            margin-bottom: 25px;
        }

        .notification-container .btn {
            background-color: #155724;
            color: white;
            font-size: 1.1rem;
            padding: 10px 25px;
            border-radius: 5px;
        }

        .notification-container .btn:hover {
            background-color: #138e3d;
        }
    </style>
</head>

<body>
    <div class="notification-container">
        <i class="fas fa-sign-out-alt fa-3x"></i>
        <h1>Logout Successful</h1>
        <p>You have successfully logged out of your account.</p>
        <a href="index.php" class="btn btn-success">Return to Login</a>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
