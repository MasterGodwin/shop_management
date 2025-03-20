<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
     
    $query = "UPDATE users SET deleted_at = 1 WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: users.php");
exit();
?>
