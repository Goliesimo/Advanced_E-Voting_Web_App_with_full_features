<?php
session_start();
include __DIR__ . '/../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = $_POST['password'];

        $selectQuery = "SELECT * FROM candidates_register WHERE username = ?";
        $stmt = $pdo->prepare($selectQuery);
        $stmt->execute([$username]);
        $candidate = $stmt->fetch();

        if ($candidate && password_verify($password, $candidate['password'])) {
            // Candidate authenticated
            $_SESSION['candidate_id'] = $candidate['id'];
            $_SESSION['candidate_username'] = $candidate['username'];
            $_SESSION['profile_picture'] = $row['profile_picture']; // Add this line

            // Redirect to candidate dashboard or any other page
            header('Location: candidate_dashboard.php');
            exit();
        } else {
            echo "<h3 class='text-danger text-center'>Invalid username or password. Login with valid details.</h3>";

        }
    } else {
        echo "Missing username or password.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Candidate Login</title>

 <link rel="stylesheet" href="../styles.css">
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <h1 class="text-center">Here is the Candidate's page</h1>
    <p class="text-center">Here aspiring candidates can rapply for candidacy with their details</p>

    <div class="container col-6">
        <form method="POST" action="login_process.php">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login" class="btn btn-secondary">
    </form>
    <a href="candidate_register.php" class="text-center">Not Registered?</a>
    </div>
    
</body>
</html>


