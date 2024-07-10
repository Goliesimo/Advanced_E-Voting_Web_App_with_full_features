<?php
include '../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if an 'id' parameter is set in the URL
    if (isset($_GET['id'])) {
        $candidateId = $_GET['id'];

        // Delete the candidate from the database based on the 'id'
        $query = "DELETE FROM candidates WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$candidateId]);

        // Redirect back to the candidates page after deletion
        header('Location: candidates.php');
        exit();
    } else {
        echo "Invalid request. No candidate ID provided.";
    }
}
?>
