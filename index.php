<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General body styling */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative; /* Position relative for the pseudo-element */
            opacity: 0; /* Initial state hidden for fade-in animation */
            animation: fadeIn 1s forwards; /* Fade-in animation for the entire body */
        }

        /* Background Image with Opacity */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('image/kld.jpg') no-repeat center center fixed;
            background-size: cover;
            opacity: 0.4; /* Adjust this value to control the opacity */
            z-index: -1; /* Place the image behind the content */
        }

        /* Fade-in animation for body */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        /* Login container animation */
        .login-container {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            border-radius: 8px;
            width: 350px;
            text-align: center;
            z-index: 1;
            animation: slideUp 1s ease-out; /* Slide-up animation for login container */
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

        /* Logo animation */
        .logo {
            width: 80px;
            margin-bottom: 20px;
            animation: scaleUp 1s ease-out 0.5s forwards; /* Scale up with delay */
        }

        /* Scale-up animation for logo */
        @keyframes scaleUp {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Heading animation */
        .login-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            animation: fadeInUp 1s ease-out 0.8s forwards; /* Fade-in up for the heading */
        }

        /* Fade-in up animation for the heading */
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

        /* Form element animations */
        .login-container form {
            display: flex;
            flex-direction: column;
            animation: fadeInUp 1s ease-out 1s forwards; /* Fade-in up for form */
        }

        /* Input field styling */
        .login-container input[type="text"], .login-container input[type="password"] {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 16px;
            width: 100%;
            opacity: 0;
            animation: fadeIn 1s ease-out 1.5s forwards; /* Fade-in effect for inputs */
        }

        /* Checkbox input styling */
        .login-container input[type="checkbox"] {
            margin-right: 8px;
        }

        /* Submit button styling */
        .login-container input[type="submit"] {
            padding: 12px;
            background-color: #5cb85c;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
            opacity: 0;
            animation: fadeIn 1s ease-out 1.7s forwards; /* Fade-in effect for submit */
        }

        /* Submit button hover effect */
        .login-container input[type="submit"]:hover {
            background-color: #4cae4c;
        }

        /* Link styling */
        .login-container a {
            margin-top: 10px;
            text-decoration: none;
            font-size: 14px;
            color: #5cb85c;
            opacity: 0;
            animation: fadeIn 1s ease-out 2s forwards; /* Fade-in effect for link */
        }

        /* Link hover effect */
        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="image/kld-logo.png" alt="Logo" class="logo">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="remember_me">
                <input type="checkbox" id="remember_me" name="remember_me"> Remember Me
            </label>
            
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>"> 

            <input type="submit" value="Login">
            <a href="registration.php">Register</a>
        </form>
    </div>
</body>
</html>
