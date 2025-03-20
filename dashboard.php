<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$name = $_SESSION['name'];
$role = $_SESSION['role'];
$firstLetter = strtoupper(substr($name, 0, 1)); 

include 'config.php';

$user_count = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$product_count = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$order_count = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("active");
        }

        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("show-dropdown");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.profile-btn')) {
                var dropdowns = document.getElementsByClassName("profile-dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show-dropdown')) {
                        openDropdown.classList.remove('show-dropdown');
                    }
                }
            }
        }
    </script>
</head>
<body>
<div id="sidebar" class="sidebar">
    <h2>Menu</h2>
    <ul>
        <li><a href="users.php">Users</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="orders.php">Orders</a></li>
    </ul>
</div>

<div class="content">

    <div class="topnav">
        <button class="menu-btn" onclick="toggleSidebar()">☰</button>
        <div class="profile-btn" onclick="toggleDropdown()">
            <?php echo $firstLetter; ?>
        </div>
        <div id="profileDropdown" class="profile-dropdown">
            <p><?php echo htmlspecialchars($name); ?></p>
            <a href="?logout=true">Logout</a>
        </div>
    </div>

    <div class="main-content" style = "background: #ececec;padding-bottom: 1345px;">
        <h1 style= "padding-top: 25px;text-align: center;">Dashboard</h1> 
        <div class="dashboard-boxes">
            <div class="box">
                <h3>Users</h3>
                <p><?php echo $user_count; ?></p>
            </div>
            <div class="box">
                <h3>Products</h3>
                <p><?php echo $product_count; ?></p>
            </div>
            <div class="box">
                <h3>Orders</h3>
                <p><?php echo $order_count; ?></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
