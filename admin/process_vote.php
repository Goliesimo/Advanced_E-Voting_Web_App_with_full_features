<?php
include 'conn.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to the login page if the user is not logged in
    exit();
}
// Check if the election ID and candidate ID are provided in the form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['election_id']) && isset($_POST['candidate_id'])) {
    $electionId = $_POST['election_id'];
    $candidateId = $_POST['candidate_id'];
    $voterId = $_SESSION['user_id'];

    // Insert the vote into the database
    $query = "INSERT INTO votes (voter_id, election_id, candidate_id) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$voterId, $electionId, $candidateId]);

    // Redirect to the elections page with a success message
    header("Location: elections.php?message=Vote successfully casted&type=success");
    exit();
} else {
    // Invalid request
    echo "Invalid request.";
    exit();
}
?>
