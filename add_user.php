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

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unique_id = uniqid();
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $query = "INSERT INTO users (unique_id, name, email, password, role) VALUES ('$unique_id', '$name', '$email', '$password', '$role')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: users.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <h2>Menu</h2>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="users.php" class="active">Users</a></li>
        <li><a href="products.php">Products</a></li>
        <li><a href="orders.php">Orders</a></li>
    </ul>
</div>

<!-- Content Wrapper -->
<div class="content">

    <!-- Top Navigation Bar -->
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

    <!-- Add User Form -->
    <div class="main-content">
        <h1 style="padding-top: 20px;">Add New User</h1>
        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" name="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="role">Role:</label>
            <select name="role" required>
                <option value="admin">Admin</option> 
                <option value="user">User</option>
            </select>

            <button type="submit" class="btn">Add User</button>
        </form>
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
