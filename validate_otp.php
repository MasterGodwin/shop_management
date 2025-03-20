<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp_entered = $_POST['otp'];
    $email = $_SESSION['email'];

    $stmt = $conn->prepare("SELECT otp FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($otp);
    $stmt->fetch();

    if ($otp_entered == $otp) {
        $_SESSION['otp_verified'] = true;
        header("Location: reset_password.php");
    } else {
        echo "<script>alert('Invalid OTP'); window.location.href='verify_otp.php';</script>";
    }
}
?>
