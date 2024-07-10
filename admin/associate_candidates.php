<?php
// Include your database connection code
include 'conn.php';
include 'getPDO.php';

// Get the PDO instance
$pdo = getPDO(); // Replace this with your actual database connection setup

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get election_id and candidate_id from the form submission
$election_id = $_POST['election'];
$candidate_id = $_POST['candidate'];

    // Check if the association already exists to avoid duplicates
    $checkQuery = "SELECT * FROM election_candidates WHERE election_id = :election_id AND candidate_id = :candidate_id";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->execute(['election_id' => $election_id, 'candidate_id' => $candidate_id]);
if ($checkStmt->rowCount() === 0) {
    // If the association doesn't exist, insert it into the database
    $insertQuery = "INSERT INTO election_candidates (election_id, candidate_id) VALUES (:election_id, :candidate_id)";
    $insertStmt = $pdo->prepare($insertQuery);
    $insertStmt->execute(['election_id' => $election_id, 'candidate_id' => $candidate_id]);

    // Redirect back to admin_dashboard.php
    header('Location: manage_elections.php');
    exit();
} else {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        Election Successfully Created
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>";
    // You may want to include a delay or meta tag for the user to read the message before redirection
    header('refresh:1;url=manage_elections.php'); 
}
}
?>
