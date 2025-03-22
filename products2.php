<?php
session_start();

if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$query = "SELECT id, unique_id, name, description, price, stock FROM products WHERE deleted_at = 0";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Dashboard</title>
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

        <h1 style = "padding-top: 20px;">Manage Products</h1>
        <a href="create_products.php" class="btn">+ Add New Products</a>

            <table>
                <thead>
                    <tr>
                        <th>ID</th> 
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th> 
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['id']}</td> 
                                    <td>{$row['name']}</td>
                                    <td>{$row['description']}</td>
                                    <td>{$row['price']}</td>
                                    <td>{$row['stock']}</td> 
                                    <td>
                                        <a href='edit_products.php?id={$row['id']}' class='edit-btn'>Edit</a>
                                        <a href='delete_product.php?id={$row['id']}' class='delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No products found.</td></tr>";
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
