<!-- Add this script to your admin_login.php -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");
        const usernameInput = document.querySelector('input[name="username"]');
        const passwordInput = document.querySelector('input[name="password"]');
        
        form.addEventListener("submit", function (e) {
            let valid = true;

            if (usernameInput.value.trim() === "") {
                valid = false;
                alert("Username is required.");
            }

            if (passwordInput.value.trim() === "" || passwordInput.value.length < 6) {
                valid = false;
                alert("Password must be at least 6 characters long.");
            }

            if (!valid) {
                e.preventDefault(); // Prevent form submission if validation fails
            }
        });
    });
</script>

<?php
include __DIR__ . '/../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id']; // Change the input name to "user_id"
    $password = $_POST['password'];

    // Verify the admin's credentials using the user ID
    $query = "SELECT id, name, password, is_admin FROM users WHERE id = ? AND is_admin = 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        // Admin login successful, set up admin session
        session_start();
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        // Redirect the admin to the admin dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        // Display an error message for failed login
        echo "Invalid admin credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <form method="post" action="admin_login.php">
            <input type="text" name="user_id" placeholder="User ID" required> <!-- Changed input name to "user_id" -->
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Log In</button>
        </form>
        <p>Return to <a href="login.php">User Login</a></p>
    </div>
</body>
</html>
