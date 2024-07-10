<?php
include 'conn.php';

// Check if the election ID is provided in the URL
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $electionId = $_GET['id'];

    // Delete related records in election_candidates
    $queryDeleteCandidates = "DELETE FROM election_candidates WHERE election_id = ?";
    $stmtDeleteCandidates = $pdo->prepare($queryDeleteCandidates);
    $stmtDeleteCandidates->execute([$electionId]);

    // Now, delete the election
    $queryDeleteElection = "DELETE FROM elections WHERE id = ?";
    $stmtDeleteElection = $pdo->prepare($queryDeleteElection);
    if ($stmtDeleteElection->execute([$electionId])) {
        // Election deleted successfully
        header('Location: manage_elections.php'); // Redirect to manage_elections page
        exit();
    } else {
        echo "Error deleting election. Please try again.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
