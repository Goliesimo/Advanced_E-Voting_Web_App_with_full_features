<?php
session_start();

// Check if the user is logged in (modify this as per your authentication logic)
if (!isset($_SESSION['candidate_id'])) {
    header('Location: login.php'); // Redirect to the login page
    exit();
}

// Include your database connection code and functions here
include 'conn.php';
include 'functions.php';
include 'getPDO.php';
$pdo = getPDO(); // Replace this with your actual database connection setup

// Perform the deletion of notifications
if (isset($_SESSION['candidate_id'])) {
    $candidateId = $_SESSION['candidate_id'];
    
    // Delete all notifications for the current candidate
    $deleteNotificationsQuery = "DELETE FROM notifications WHERE candidate_id = ?";
    $stmt = $pdo->prepare($deleteNotificationsQuery);
    if ($stmt->execute([$candidateId])) {
        // Notifications deleted successfully
        header('Location: candidate_dashboard.php'); // Redirect to the dashboard
        exit();
    } else {
        echo "Failed to delete notifications.";
    }
}
?>
