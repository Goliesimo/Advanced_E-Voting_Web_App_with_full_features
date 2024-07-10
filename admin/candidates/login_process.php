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
            echo "Invalid username or password. Please try again.";
        }
    } else {
        echo "Missing username or password.";
    }
}
?>

