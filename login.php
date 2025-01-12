<?php
session_start();
require __DIR__ . '/config/db.php'; // MongoDB connection

// Check if user is already logged in
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php"); // Redirect to dashboard if logged in
    exit();
}

// Handle login request
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connect to MongoDB
    $db = connectMongoDB();
    $usersCollection = $db->users;

    // Find the user in the database
    $user = $usersCollection->findOne(['email' => $email]);

    // Verify password and start session if correct
    if ($user && password_verify($password, $user['password'])) {
        // Store user data in session
        $_SESSION['user'] = [
            'id' => (string) $user['_id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        
        // Redirect based on user role
        if ($user['role'] == 'admin') {
            header("Location: manage_products.php"); // Admin redirects to manage products
        } else {
            header("Location: view_products.php"); // Regular user redirects to view products
        }
        exit();
    } else {
        // Invalid credentials
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dotmini Store</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Inline Modern Styling for Login Page */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff5722, #ff8a50);
            color: #212121;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background: #212121;
            color: white;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }

        .navbar a:hover {
            color: #ff5722;
        }

        header {
            text-align: center;
            margin: 30px 0;
            color: white;
        }

        .container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .container h1 {
            margin-bottom: 20px;
            color: #212121;
        }

        .container label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .container .button {
            width: 100%;
            padding: 10px;
            background: #ff5722;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .container .button:hover {
            background: #e64a19;
        }

        .container p {
            text-align: center;
            margin-top: 20px;
            color: #757575;
        }

        .container a {
            color: #ff5722;
            text-decoration: none;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            color: white;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </div>

    <header>
        <h1>Login to Your Account</h1>
    </header>

    <div class="container">
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>

            <button type="submit" name="login" class="button">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>

    <footer>
        <p>&copy; 2025 Dotmini Software</p>
    </footer>
</body>
</html>