<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <form method="POST" action="send_otp.php">
            <label>Email:</label>
            <input type="email" name="email" required>
            <button type="submit">Send OTP</button>
        </form>
        <a href="login.php">Back to Login</a>
    </div>
</body>
</html>
    