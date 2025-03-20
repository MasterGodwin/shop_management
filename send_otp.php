<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        $stmt = $conn->prepare("UPDATE users SET otp = ? WHERE email = ?");
        $stmt->bind_param("is", $otp, $email);
        $stmt->execute();

        echo "<script>alert('OTP sent to your email'); window.location.href='verify_otp.php';</script>";
    } else {
        echo "<script>alert('Email not found'); window.location.href='forgot_password.php';</script>";
    }
}
?>
