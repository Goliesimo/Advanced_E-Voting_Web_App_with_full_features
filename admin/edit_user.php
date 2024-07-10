<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id'])) {
    // Retrieve the user's information
    $userId = $_GET['user_id'];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch();
    } else {
        echo "User not found.";
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Handle the user information update
    $userId = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['name'];
    $password = $_POST['password']; // Remember to hash the password securely before storing it in the database

    // Implement user information update logic here
    // Example: You can use an SQL query to update the user's details in the database
    $updateQuery = "UPDATE users SET name = ?, email = ?, name = ?, password = ? WHERE id = ?";
    $stmt = $pdo->prepare($updateQuery);
    // Remember to hash the password securely before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    if ($stmt->execute([$name, $email, $name, $hashedPassword, $userId])) {
          // User information updated successfully
        $_SESSION['success_message'] = "User profile successfully updated.";
        header('Location: manage_users.php'); // Redirect to manage_users page
        exit();
    } else {
        $_SESSION['error_message'] = "User information update failed. Please try again.";
        header('Location: manage_users.php'); // Redirect to manage_users page with error message
        exit();
    }
}

?>

<!-- Rest of your HTML content -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../styles.css">
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>


</head>
<body>
    <h1>Edit User</h1>
         <div id="success-message" class="alert alert-success" style="display: none;">
    Profile successfully updated.
</div>

    <div class="container">
        <form action="edit_user.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
            <div class="form-group">
                <label for="name">User Name:</label>
                <input type="text" class="form-control" name="name" value="<?php echo $user['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" name="update" class="btn btn-outline-success">Update User</button>
        </form>
    </div>
  
<script>
    // Function to show success message and redirect
    function showSuccessMessageAndRedirect() {
        // Show the success message
        $('#success-message').show();

        // Redirect to manage_users.php after a delay (e.g., 2 seconds)
        setTimeout(function () {
            window.location.href = 'manage_users.php';
        }, 2000); // 2000 milliseconds = 2 seconds
    }

    // jQuery function to handle the form submission
    $(document).ready(function () {
        $('form').submit(function (event) {
            event.preventDefault();
            // Submit the form using AJAX
            $.ajax({
                type: 'POST',
                url: 'edit_user.php',
                data: $(this).serialize(),
                success: function (response) {
                    if (response === 'success') {
                        showSuccessMessageAndRedirect();
                    } else {
                        // Handle other cases
                        console.log(response);
                    }
                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
    
</body>
</html>
