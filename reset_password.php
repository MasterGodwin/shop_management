<?php
session_start();
include 'config.php';

if (!isset($_SESSION['otp_verified'])) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_SESSION['email'];

    $stmt = $conn->prepare("UPDATE users SET password = ?, otp = NULL WHERE email = ?");
    $stmt->bind_param("ss", $new_password, $email);

    if ($stmt->execute()) {
        session_destroy();
        echo "<script>alert('Password updated successfully! Please login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error updating password');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form method="POST" action="">
            <label>New Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Update Password</button>
        </form>
    </div>
</body>
</html>
