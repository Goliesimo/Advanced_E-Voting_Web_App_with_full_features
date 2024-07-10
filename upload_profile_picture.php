<?php
// Include your database connection code
include 'conn.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $uploadDir = 'profile_pictures/';
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    $fileName = basename($_FILES['profile_picture']['name']);
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

    if (in_array($fileType, $allowedTypes)) {
        $uniqueName = uniqid() . '.' . $fileType;
        $filePath = $uploadDir . $uniqueName;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $filePath)) {
            $updateQuery = "UPDATE users SET profile_picture = :profile_picture WHERE id = :user_id";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->execute(['profile_picture' => $uniqueName, 'user_id' => $user_id]);

            header('Location: dashboard.php?success=profile_picture_uploaded');
            exit();
        } else {
            header('Location: dashboard.php?error=file_upload_failed');
            exit();
        }
    } else {
        header('Location: dashboard.php?error=invalid_file_type');
        exit();
    }
} else {
    header('Location: dashboard.php?error=invalid_request');
    exit();
}
?>
