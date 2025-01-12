<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$userRole = $_SESSION['user']['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">Dotmini Admin</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <header class="header">
        <h1>Welcome, <?php echo $_SESSION['user']['username']; ?></h1>
    </header>

    <div class="container">
        <?php if ($userRole == 'admin'): ?>
            <div class="card">
                <h2>Admin Dashboard</h2>
                <a href="admin/manage_products.php" class="button">Manage Products</a>
            </div>
        <?php else: ?>
            <div class="card">
                <h2>User Dashboard</h2>
                <a href="products.php" class="button">Shop Now</a>
            </div>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Dotmini Software</p>
    </footer>
</body>
</html>