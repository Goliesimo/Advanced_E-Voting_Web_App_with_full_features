<?php
include __DIR__ . '/../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id']) && isset($_FILES['profile_pictures'])) {
        $userId = $_POST['user_id'];
        $profilePicture = $_FILES['profile_pictures'];

$uploadDirectory = __DIR__ . '/profile_pictures/'; // Update this with the correct directory

 

        // Check if the uploaded file is an image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($profilePicture['type'], $allowedTypes)) {
            // Generate a unique filename for the uploaded image
            $filename = uniqid() . '_' . $profilePicture['name'];

            // Move the uploaded image to the profile pictures directory
            if (move_uploaded_file($profilePicture['tmp_name'], $uploadDirectory . $filename)) {
                // Update the user's profile picture in the database
                $updateQuery = "UPDATE users SET profile_pictures = ? WHERE id = ?";
                $stmt = $pdo->prepare($updateQuery);
                if ($stmt->execute([$uploadDirectory . $filename, $userId])) {
                    // Profile picture updated successfully
                    header('Location: manage_users.php'); // Redirect back to manage_users.php
                    exit();
                } else {
                    echo "Failed to update the profile picture in the database.";
                }
            } else {
                echo "Failed to move the uploaded image to the server.";
            }
        } else {
            echo "Invalid file type. Only JPEG, PNG, and GIF images are allowed.";
        }
    } else {
        echo "Missing user ID or profile picture data.";
    }
} else {
    echo "Invalid request method.";
}
?>
