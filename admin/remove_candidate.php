<?php
include 'conn.php';
include 'getPDO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $electionId = $_POST['election_id'];
    $candidateId = $_POST['candidate_id'];

    // Remove the association from the database
    $deleteQuery = "DELETE FROM election_candidates WHERE election_id = :election_id AND candidate_id = :candidate_id";
    $deleteStmt = $pdo->prepare($deleteQuery);
    $deleteStmt->execute(['election_id' => $electionId, 'candidate_id' => $candidateId]);
    
    // Redirect back to the manage_elections.php page
    header('Location: manage_elections.php');
    exit();
}
?>
