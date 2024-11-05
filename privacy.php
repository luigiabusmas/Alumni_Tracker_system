<?php 
session_start();

// If user is already logged in, redirect to dashboard

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
    <link rel="stylesheet" href="./resources/styles.css"> 
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 20px;
        }
        .btn {
            background-color: #f2b129;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #e09e22;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Privacy Policy Agreement</h1>
    <p>
        We value your privacy. By accessing this system, you agree to our data collection and usage policies as outlined in our Privacy Notice.
    </p>
    <p>
        Please read and understand our policies before proceeding.
    </p>
    <p>
        Do you agree to proceed?
    </p>
    <a href="dashboard.php" class="btn">I Agree</a>
    <a href="logout.php" class="btn">I Do Not Agree</a>
</div>

</body>
</html>
