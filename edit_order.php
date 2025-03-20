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
$message = "";

$query = "SELECT id, status FROM orders WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    header("Location: orders.php");
    exit();
}

// Update order status
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_status = $_POST['status'];
    $update_query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        $message = "Order status updated successfully!";
        $order['status'] = $new_status;
    } else {
        $message = "Error updating status.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>


    <div class="sidebar" id="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="orders.php">Orders</a></li>
        </ul>
    </div>

    <div class="content">

    <div class="topnav">
            <button class="menu-btn" onclick="toggleSidebar()">☰</button>
            <div class="profile-btn" onclick="toggleDropdown()">
                <?php echo strtoupper(substr($_SESSION['name'], 0, 1)); ?>
            </div>
            <div id="profileDropdown" class="profile-dropdown">
                <p><?php echo htmlspecialchars($_SESSION['name']); ?></p>
                <a href="?logout=true">Logout</a>
            </div>
        </div>

        <div class="main-content">

        <h1 style="padding-top: 20px;">Edit Order Status</h1> <br>

        <?php if ($message): ?>
            <p style="color: green;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="status">Order Status:</label>
            <select name="status" id="status">
                <option value="Pending" <?php if ($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Processing" <?php if ($order['status'] == 'Processing') echo 'selected'; ?>>Processing</option>
                <option value="Shipped" <?php if ($order['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                <option value="Delivered" <?php if ($order['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                <option value="Cancelled" <?php if ($order['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
            </select>

            <button type="submit">Update Status</button>
        </form>

        <br>
        <a href="orders.php">Back to Orders</a>
    </div>
</div>
<script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("active");
        }

        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("show-dropdown");
        }
    </script>
</body>
</html>
