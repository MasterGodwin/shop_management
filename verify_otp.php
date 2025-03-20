<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: forgot_password.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Verify OTP</h2>
        <form method="POST" action="validate_otp.php">
            <label>Enter OTP:</label>
            <input type="number" name="otp" required>
            <button type="submit">Verify</button>
        </form>
    </div>
</body>
</html>
