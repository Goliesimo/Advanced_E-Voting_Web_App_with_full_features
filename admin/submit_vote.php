<?php
// Include your database connection code
include 'conn.php';
include 'getPDO.php';

// Get the PDO instance
$pdo = getPDO(); // Replace this with your actual database connection setup

session_start();

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Initialize the success parameter
$successParam = isset($_GET['success']) ? $_GET['success'] : null;

// Check if the vote form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote'])) {
    // Retrieve values from the form
    $electionId = $_POST['election_id'];
    $candidateId = $_POST['candidate_id'];

    // Check if the voter_id is set in the session
    if (isset($_SESSION['user_id'])) {
        // Fetch voter_id from the users table
        $userId = $_SESSION['user_id'];

        $userQuery = "SELECT voter_id, status FROM users WHERE id = :user_id";
        $userStmt = $pdo->prepare($userQuery);
        $userStmt->execute(['user_id' => $userId]);
        $userInfo = $userStmt->fetch(PDO::FETCH_ASSOC);

        if ($userInfo && $userInfo['status'] === 'accredited') {
            // User is accredited, proceed with the vote

            $voterId = $userInfo['voter_id'];

            // Check if the user has already voted in this election
            $checkElectionVoteQuery = "SELECT * FROM votes WHERE voter_id = :voter_id AND election_id = :election_id";
            $checkElectionVoteStmt = $pdo->prepare($checkElectionVoteQuery);
            $checkElectionVoteStmt->execute(['voter_id' => $voterId, 'election_id' => $electionId]);

            if ($checkElectionVoteStmt->rowCount() === 0) {
                // User has not voted in this election, proceed with the vote

                // Check if the user has already voted for this candidate in this election
                $checkVoteQuery = "SELECT * FROM votes WHERE voter_id = :voter_id AND election_id = :election_id AND candidate_id = :candidate_id";
                $checkVoteStmt = $pdo->prepare($checkVoteQuery);
                $checkVoteStmt->execute(['voter_id' => $voterId, 'election_id' => $electionId, 'candidate_id' => $candidateId]);

                if ($checkVoteStmt->rowCount() === 0) {
                    // User has not voted for this candidate in this election, proceed with the vote

                    // Fetch additional information from other tables
                    $candidateQuery = "SELECT name, photo FROM candidates WHERE id = :candidate_id";
                    $candidateStmt = $pdo->prepare($candidateQuery);
                    $candidateStmt->execute(['candidate_id' => $candidateId]);
                    $candidateInfo = $candidateStmt->fetch(PDO::FETCH_ASSOC);

                    $electionQuery = "SELECT name FROM elections WHERE id = :election_id";
                    $electionStmt = $pdo->prepare($electionQuery);
                    $electionStmt->execute(['election_id' => $electionId]);
                    $electionInfo = $electionStmt->fetch(PDO::FETCH_ASSOC);

                    // Insert the vote with additional information
                    $insertVoteQuery = "INSERT INTO votes (voter_id, election_id, candidate_id, candidate_name, candidate_picture, election_name) VALUES (:voter_id, :election_id, :candidate_id, :candidate_name, :candidate_picture, :election_name)";
                    $insertVoteStmt = $pdo->prepare($insertVoteQuery);

                    // Execute the vote insertion
                    $insertVoteStmt->execute([
                        'voter_id' => $voterId,
                        'election_id' => $electionId,
                        'candidate_id' => $candidateId,
                        'candidate_name' => $candidateInfo['name'],
                        'candidate_picture' => $candidateInfo['photo'],
                        'election_name' => $electionInfo['name']
                    ]);

                    // Redirect back to vote.php with a success message
                    header('Location: vote.php?success=1');
                    exit();

                } else {
                    // User has already voted for this candidate in this election
                    $alertMessage = "Error: You have already voted for this candidate in this election!";
                    header('Location: vote.php?error=' . urlencode($alertMessage));
                    exit();
                }
            } else {
                // User has already voted in this election
                $alertMessage = "Error: You have already voted in this election!";
                header('Location: vote.php?error=' . urlencode($alertMessage));
                exit();
            }
        } else {
            // User is not accredited, show an error message
            $alertMessage = "Error: You are not accredited to vote!";
            header('Location: vote.php?error=' . urlencode($alertMessage));
            exit();
        }
    } else {
        // Handle the case where voter_id is not found in the users table
        $alertMessage = "Error: Voter ID not found!";
        header('Location: vote.php?error=' . urlencode($alertMessage));
        exit();
    }
} else {
    // If the form is not submitted, redirect to vote.php
    header('Location: vote.php');
    exit();
}
?>
