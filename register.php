<?php
session_start();
require __DIR__ . '/config/db.php'; // MongoDB connection

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php"); // Redirect to dashboard if logged in
    exit();
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the user into the database
        $db = connectMongoDB();
        $usersCollection = $db->users;

        $existingUser = $usersCollection->findOne(['email' => $email]);

        if ($existingUser) {
            $error = "Email is already taken.";
        } else {
            $usersCollection->insertOne([
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword,
                'role' => 'user', // Default role is 'user'
            ]);
            header("Location: login.php"); // Redirect to login page after registration
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Dotmini Store</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Inline Modern Styling for Register Page */
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
        <h1>Create Your Account</h1>
    </header>

    <div class="container">
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="Enter your username" required>

            <label for="email">Email Address</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>

            <button type="submit" name="register" class="button">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <footer>
        <p>&copy; 2025 Dotmini Software</p>
    </footer>
</body>
</html>