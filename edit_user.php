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
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$user_id = $_GET['id'];
 
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: users.php");
    exit();
}

$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);

    if (!empty($name) && !empty($email) && !empty($role)) {
        $update_stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $update_stmt->bind_param("sssi", $name, $email, $role, $user_id);

        if ($update_stmt->execute()) {
            $_SESSION['message'] = "User updated successfully!";
            header("Location: users.php");
            exit();
        } else {
            $error_message = "Error updating user!";
        }
    } else {
        $error_message = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
        <h1>Edit User</h1>

        <?php if (isset($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>

        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label>Role:</label>
            <select name="role" required>
                <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
            </select>

            <button type="submit" class="btn">Update User</button>
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
