<?php
session_start();

if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "UPDATE products SET deleted_at = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: products.php?success=Product deleted successfully");
    } else {
        header("Location: products.php?error=Failed to delete product");
    }

    $stmt->close();
} else {
    header("Location: products.php?error=Invalid request");
    exit();
}
?>
