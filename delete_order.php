<?php
session_start();
include 'config.php';

if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['id'];
 
$query = "UPDATE orders SET deleted_at = 1 WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);

if ($stmt->execute()) {
    header("Location: orders.php");
    exit();
} else {
    echo "Error deleting order.";
}
?>
