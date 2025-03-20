<?php
session_start();

if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

include 'config.php';

$query = "SELECT orders.id, orders.unique_id, users.name AS user_name, products.name AS product_name, 
                 orders.quantity, orders.status, orders.created_at 
          FROM orders 
          JOIN users ON orders.user_id = users.id 
          JOIN products ON orders.product_id = products.id 
          WHERE orders.deleted_at = 0";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Dashboard</title>
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
            <h1 style="padding-top: 20px;">Manage Orders</h1>

            <table>
                <thead>
                    <tr>
                        <th>ID</th> 
                        <th>User</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Status</th> 
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['id']}</td> 
                                    <td>{$row['user_name']}</td>
                                    <td>{$row['product_name']}</td>
                                    <td>{$row['quantity']}</td>
                                    <td>{$row['status']}</td> 
                                    <td>
                                        <a href='edit_order.php?id={$row['id']}' class='edit-btn'>Edit</a>
                                        <a href='delete_order.php?id={$row['id']}' class='delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No orders found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
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
