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
    <p>Here aspiring candidates can rapply for candidacy with their details</p>

    <div class="container col-6">
        <form method="POST" action="login_process.php">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login" class="btn btn-secondary">
    </form>
    <a href="candidate_register.php">Not Registered?</a>
    </div>
    
</body>
</html>
