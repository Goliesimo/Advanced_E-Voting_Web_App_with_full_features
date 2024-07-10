<?php
include '../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $userId = $_GET['id'];
    $query = "SELECT id, name, email, username FROM users WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($user); // Return user details as JSON
    } else {
        echo json_encode(["error" => "User not found"]);
    }
}
?>
