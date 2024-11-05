<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS here -->
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="remember_me">
                <input type="checkbox" id="remember_me" name="remember_me"> Remember Me
            </label>
            
            <!-- Include a hidden input for the CSRF token -->
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>"> 

            <input type="submit" value="Login">
            <a href="registration.php">Register</a>
        </form>
    </div>
</body>
</html>
