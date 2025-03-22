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

include 'config2.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $unique_id = uniqid(); 
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $created_at = date("Y-m-d H:i:s");

        $query = "INSERT INTO products (unique_id, name, description, price, stock, created_at, deleted_at) 
                  VALUES (:unique_id, :name, :description, :price, :stock, :created_at, 0)";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':unique_id', $unique_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':created_at', $created_at);

        if ($stmt->execute()) {
            header("Location: products.php?success=Product added successfully");
            exit();
        } else {
            $error = "Error adding product.";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="dashboard.css">
    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("active");
        }

        function toggleDropdown() {
            document.getElementById("profileDropdown").classList.toggle("show-dropdown");
        }

        // Close dropdown when clicking outside
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

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
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
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <div class="main-content">
            <h1 style="padding-top: 20px;">Add New Product</h1>

            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

            <form method="POST" action="">
                <label>Product Name:</label>
                <input type="text" name="name" required>

                <label>Description:</label>
                <textarea name="description" required></textarea>

                <label>Price:</label>
                <input type="number" name="price" step="0.01" required>

                <label>Stock:</label>
                <input type="number" name="stock" required>

                <button type="submit" class="btn">Add Product</button>
            </form>
        </div>
    </div>

</body>
</html>
