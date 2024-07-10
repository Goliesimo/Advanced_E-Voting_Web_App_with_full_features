<?php
session_start();
include __DIR__ . '/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure all form fields are present and not empty
    if (
        isset($_POST['username']) &&
        isset($_POST['password']) &&
        isset($_POST['confirm_password']) &&
        isset($_POST['email']) &&
        isset($_FILES['profile_picture'])
    ) {
        // Validate and sanitize the input data
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

        // Check if the password and confirm_password fields match
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($password !== $confirmPassword) {
            echo "Password and Confirm Password do not match.";
            exit();
        }

        // Check if the candidate with the same username or email already exists
        $checkQuery = "SELECT * FROM candidates_register WHERE username = ? OR email = ?";
        $stmt = $pdo->prepare($checkQuery);
        $stmt->execute([$username, $email]);

        if ($stmt->fetch()) {
            echo "Candidate with the same username or email already exists. Please use a different username or email. <br><a href='login.php'>Go back<a/> </br";
            exit();
        }

        // Hash the password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Define the directory to store profile pictures
        $uploadDirectory = __DIR__ . '/profile_pictures/';

        // Check if the uploaded file is an image
        if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $profilePicture = $_FILES['profile_picture'];
            $filename = uniqid() . '_' . $profilePicture['name'];

            // Move the uploaded image to the profile pictures directory
            if (move_uploaded_file($profilePicture['tmp_name'], $uploadDirectory . $filename)) {
                // Insert user data into the database
                $insertQuery = "INSERT INTO candidates_register (username, password, email, profile_picture) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($insertQuery);
                if ($stmt->execute([$username, $hashedPassword, $email, 'profile_pictures/' . $filename])) {
                    // Registration successful
                    echo "Registration successful. Please wait for approval from the admin.<br>";
                    echo '<a href="login.php">Login</a> | <a href="../index.php">Go to Homepage</a>';
                    exit();
                } else {
                    echo "Failed to register the candidate. Please try again.";
                }
            } else {
                echo "Failed to move the uploaded image to the server.";
            }
        } else {
            echo "Invalid file upload.";
        }
    } else {
        echo "Missing or invalid data in the registration form.";
    }
}
?>
