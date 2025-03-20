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

$result = $conn->query("SELECT * FROM users WHERE deleted_at = 0");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div id="sidebar" class="sidebar">
    <h2>Menu</h2>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="users.php" class="active">Users</a></li>
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
        <h1 style = "padding-top: 20px;">Manage Users</h1>
        <a href="add_user.php" class="btn">+ Add New User</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                        <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
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
