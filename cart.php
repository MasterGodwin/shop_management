<?php
session_start();
include 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $status = "pending"; 
    $unique_id = uniqid("ORD_"); 
    $created_at = date("Y-m-d H:i:s");
    $deleted_at = 0;

    $user_query = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $user_query->bind_param("i", $user_id);
    $user_query->execute();
    $user_result = $user_query->get_result();
    $user_data = $user_result->fetch_assoc();
    $user_email = $user_data['email'];

    $stock_query = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $stock_query->bind_param("i", $product_id);
    $stock_query->execute();
    $stock_result = $stock_query->get_result();
    $row = $stock_result->fetch_assoc();
    $stock_available = $row['stock'];

    if ($quantity > $stock_available) {
        echo "Error: Not enough stock available!";
        exit(); 
    }

    $insert_order = $conn->prepare("INSERT INTO orders (unique_id, user_id, product_id, quantity, status, created_at, deleted_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insert_order->bind_param("siisssi", $unique_id, $user_id, $product_id, $quantity, $status, $created_at, $deleted_at);

    if ($insert_order->execute()) {
        $update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $update_stock->bind_param("ii", $quantity, $product_id);
        $update_stock->execute();

        echo "Order placed successfully!";
        header("Location: home.php");
    } else {
        echo "Error placing order!";
    }
}
?>
