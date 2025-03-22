<?php
session_start();

if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

include 'config2.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $query = "UPDATE products SET deleted_at = 1 WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->execute([':id' => $id]);

        header("Location: products.php?success=Product deleted successfully");
        exit();
    } catch (PDOException $e) {
        header("Location: products.php?error=Failed to delete product: " . $e->getMessage());
        exit();
    }
} else {
    header("Location: products.php?error=Invalid request");
    exit();
}
?>
