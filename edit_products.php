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

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = $_GET['id'];

try {
    $query = "SELECT * FROM products WHERE id = :id AND deleted_at = 0";
    $stmt = $conn->prepare($query);
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header("Location: products.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];

        $update_query = "UPDATE products SET name = :name, description = :description, price = :price, stock = :stock WHERE id = :id";
        $stmt = $conn->prepare($update_query);
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':stock' => $stock,
            ':id' => $id
        ]);

        header("Location: products.php");
        exit();
    }
} catch (PDOException $e) {
    $error = "Error updating product: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="dashboard.css">
    <script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("active");
    }

    function toggleDropdown() {
        document.getElementById("profileDropdown").classList.toggle("show-dropdown");
    }
</script>
</head>
<body>

    <div id="sidebar" class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="products.php" class="active">Products</a></li>
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
            <h1 style = "padding-top: 20px;">Edit Product</h1>

            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

            <form method="POST" action="">
                <label>Product Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

                <label>Description:</label>
                <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

                <label>Price:</label>
                <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>

                <label>Stock:</label>
                <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>

                <button type="submit" class="btn">Update Product</button>
            </form>
        </div>
    </div>

</body>
</html>
