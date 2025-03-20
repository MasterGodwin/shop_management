<?php
session_start();
include 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

// Fetch ordered items from the orders table
$order_sql = "SELECT orders.id, products.name, orders.quantity, orders.status, products.price, (orders.quantity * products.price) AS total_price 
              FROM orders 
              JOIN products ON orders.product_id = products.id 
              WHERE orders.user_id = ?";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Orders</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
}

.container {
    width: 80%;
    margin: 30px auto;
    background: white;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h2 {
    text-align: center;
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #007bff;
    color: white;
}

tr:hover {
    background-color: #f1f1f1;
}

.place-order-btn {
    display: block;
    width: 100%;
    background: #28a745;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 5px;
    text-align: center;
    font-size: 16px;
    cursor: pointer;
    margin-top: 20px;
}

.place-order-btn:hover {
    background: #218838;
}

    </style>
</head>
<body>
    <header>
        <h1>Your Orders</h1>
        <a href="home.php">Back to Home</a>
    </header>
    <section>
        <table border="1">
            <tr> 
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            <?php while ($order = $orders_result->fetch_assoc()) { ?>
                <tr> 
                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td>₹<?php echo $order['price']; ?></td>
                    <td>₹<?php echo $order['total_price']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </section>
</body>
</html>
