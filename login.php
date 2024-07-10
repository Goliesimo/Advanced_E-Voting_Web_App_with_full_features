
<?php
include 'conn.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginIdentifier = $_POST['login_identifier'];
    $password = $_POST['password'];

    // Check whether the provided identifier is an email or a Voter ID
    $loginField = filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'voter_id';

    // Verify the user's credentials and check if the user is not an admin
    $query = "SELECT id, name, email, password, voter_id FROM users WHERE $loginField = ? AND is_admin = 0";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$loginIdentifier]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Regular user login successful, set up user session
        session_start();
        $_SESSION['user_id'] = $user['id']; // Store user ID
        $_SESSION['user_name'] = $user['name']; // Store user name
        $_SESSION['voter_id'] = $voterId['voter_id']; // Store user name
        // After successful authentication
        $_SESSION['voter_id'] = $voterId;

        // Redirect the user to the dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        // Display an error message for failed login
        echo "Invalid login credentials. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<style>
    body{
        background: crimson;
    }
</style>
<body>
    <div class="container">
        <h2>User Login</h2>
        <!-- Your HTML login form that includes both email and Voter ID fields -->
        <form method="post" action="login.php">
            <input type="text" name="login_identifier" placeholder="Email or Voter ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Log In</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>


    <!-- Add this script to your login.php -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");
        const emailInput = document.querySelector('input[name="email"]');
        const passwordInput = document.querySelector('input[name="password"]');
        
        form.addEventListener("submit", function (e) {
            let valid = true;

            if (emailInput.value.trim() === "" || !emailInput.value.includes("@")) {
                valid = false;
                alert("Valid email is required.");
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


 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
