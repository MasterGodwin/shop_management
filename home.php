<?php
session_start();
include 'config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

$sql = "SELECT id, name, description, price, stock FROM products";
$result = $conn->query($sql);

$order_sql = "SELECT orders.id, products.name, orders.quantity 
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
    <title>Home - eCommerce</title>
    <link rel="stylesheet" href="home.css">
    <style> 
        .cart-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .cart-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 200px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            padding: 10px;
            z-index: 1;
            left: 0;  
        }

        .cart-dropdown:hover .cart-content {
            display: block;
        }

        .cart-content ul {
            list-style: none;
            padding: 0;
        }

        .cart-content li {
            padding: 5px 0;
        }

        .cart-content button {
            width: 100%;
            padding: 5px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }

        .profile-dropdown {
            position: relative;
            display: inline-block;
            margin-left: 20px; 
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 200px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            padding: 10px;
            z-index: 1;
            right: 0;
        }

        .profile-dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <h1 class="logo">Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
            <nav class="nav-links">
                <a href="home.php">Home</a>

                <div class="cart-dropdown">
                    <button class="cart-btn">🛒 Cart ▼</button>
                    <div class="cart-content">
                        <h3>Your Orders</h3>
                        <ul>
                            <?php while ($order = $orders_result->fetch_assoc()) { ?>
                                <li><?php echo htmlspecialchars($order['name']) . " - " . $order['quantity']; ?></li>
                            <?php } ?>
                        </ul>
                        <form action="place_order.php" method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                            <button type="submit">Place Order</button>
                        </form>
                    </div>
                </div>

                <div class="profile-dropdown">
                    <button class="profile-btn">👤 Profile ▼</button>
                    <div class="dropdown-content">
                        <p><strong><?php echo htmlspecialchars($name); ?></strong></p>
                        <p><?php echo htmlspecialchars($email); ?></p>
                        <a href="?logout=true">🚪 Logout</a>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <section class="products">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <form action="cart.php" method="POST">
                <div class="product"> 
                    <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <p><strong>₹<?php echo htmlspecialchars($row['price']); ?></strong></p>
                    <p><strong>Stock: <?php echo $row['stock']; ?></strong></p>

                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($row['name']); ?>">
                    <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                    <div class="quantity">
                        <button type="button" onclick="decreaseQuantity(this)">-</button>
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $row['stock']; ?>">
                        <button type="button" onclick="increaseQuantity(this, <?php echo $row['stock']; ?>)">+</button>
                    </div>

                    <button type="submit" <?php echo ($row['stock'] == 0) ? 'disabled' : ''; ?>>Add to Cart</button>
                </div>
            </form>
        <?php } ?>
    </section>

    <script>
        function increaseQuantity(button, maxStock) {
            let input = button.previousElementSibling;
            if (parseInt(input.value) < maxStock) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function decreaseQuantity(button) {
            let input = button.nextElementSibling;
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
    </script>
</body>
</html>
