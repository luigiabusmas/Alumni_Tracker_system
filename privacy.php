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
        /* Body animation - Fade in and position */
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
            opacity: 0; /* Start with hidden */
            animation: fadeIn 1s forwards; /* Fade in effect for the body */
        }

        /* Fade-in animation for the body */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        /* Container animation - Slide up and fade in */
        .container {
            max-width: 600px;
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            opacity: 0;
            animation: slideUp 1s ease-out forwards 0.5s; /* Slide-up and fade-in effect */
            display: flex;
            flex-direction: column;
            align-items: center; /* Center the content */
        }
        .container .btn-container {
    display: flex;
    justify-content: center; /* Center the buttons */
    gap: 10px; /* Add some space between the buttons */
    margin-top: 20px;
}

        /* Slide-up animation */
        @keyframes slideUp {
            0% {
                transform: translateY(30px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Heading animation - Fade-in from the top */
        h1 {
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeInUp 1s ease-out forwards 1s; /* Fade-in with upward movement */
        }

        /* Fade-in and move up animation for the heading */
        @keyframes fadeInUp {
            0% {
                transform: translateY(20px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Paragraph animation - Fade-in with slight delay */
        p {
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards 1.5s; /* Fade-in for paragraphs */
        }

        /* Button animation - Bounce effect */
        .btn {
            background-color: #f2b129;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards 2s; /* Fade-in effect with delay for buttons */
            transition: background-color 0.3s;
        }

        /* Button hover effect */
        .btn:hover {
            background-color: #e09e22;
        }

        /* Add some margin between buttons */
        .btn + .btn {
            margin-top: 10px;
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
