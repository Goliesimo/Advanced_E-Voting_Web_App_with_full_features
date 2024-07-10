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
        $voterId = $_SESSION['user_id'];

          // Check if the voter_id exists in the voters table
    $checkVoterQuery = "SELECT * FROM voters WHERE id = :voter_id";
    $checkVoterStmt = $pdo->prepare($checkVoterQuery);
    $checkVoterStmt->execute(['voter_id' => $voterId]);

     if ($checkVoterStmt->rowCount() === 0) {
        // Insert the vote
        $insertVoteQuery = "INSERT INTO votes (voter_id, election_id, candidate_id) VALUES (:voter_id, :election_id, :candidate_id)";
        $insertVoteStmt = $pdo->prepare($insertVoteQuery);
        $insertVoteStmt->execute(['voter_id' => $voterId, 'election_id' => $electionId, 'candidate_id' => $candidateId]);

        // Insert the vote
        $insertVoteQuery = "INSERT INTO votes (voter_id, election_id, candidate_id) VALUES (:voter_id, :election_id, :candidate_id)";
        $insertVoteStmt = $pdo->prepare($insertVoteQuery);
        $insertVoteStmt->execute(['voter_id' => $voterId, 'election_id' => $electionId, 'candidate_id' => $candidateId]);

        // Add a success message or redirect as needed
        $alertMessage = "Vote casted successfully!";
    } 

        // Redirect back to vote.php with a success message
        header('Location: vote.php?success=1');
        exit();
    } else {
        // Handle the case where voter_id does not exist in the voters table
        $alertMessage = "Error: Invalid Voter ID!";
        header('Location: vote.php?error=' . urlencode($alertMessage));
        exit();
    }
} else {

        
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


<script>
    function showSuccessAlert() {
        $('#successAlert').removeClass('d-none');
    }

    function hideSuccessAlert() {
        $('#successAlert').addClass('d-none');
    }
</script>


    <!-- Add Bootstrap JavaScript and jQuery links -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 
</head>
<body>
     <div class="container mt-4">
    <!-- Bootstrap Alert (for displaying success messages after voting) -->
    <?php if ($successParam === '1'): ?>
        <div class="alert alert-success" role="alert">
            You have successfully casted your vote for this candidate.
            <button type="button" class="close" onclick="hideSuccessAlert()" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
</div>
    <div class="container">
    <h1>Vote in Elections</h1>
    <form method="post" action="vote.php">
        <div class="form-group">
            <label for="electionSelect">Select Election:</label>
            <select class="form-control" id="electionSelect" name="election_id">
                <?php
                // Query the database to retrieve all existing elections
                $selectQuery = "SELECT * FROM elections";
                $stmt = $pdo->query($selectQuery);
                $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($elections as $election) {
                    echo '<option value="' . $election['id'] . '">' . $election['name'] . '</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit" name="view_candidates" class="btn btn-outline-primary">View Candidates</button>
        <a href="vote.php" class="btn btn-outline-success">Reload</a>
    </form>

    <!-- ... (Previous code) -->

    <?php
    // If the form to view candidates is submitted, display candidates
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['view_candidates'])) {
        // Retrieve candidates for the selected election
        $selectedElectionId = $_POST['election_id'];
        $candidatesQuery = "SELECT candidates.*, election_candidates.election_id FROM candidates
                            JOIN election_candidates ON candidates.id = election_candidates.candidate_id
                            WHERE election_candidates.election_id = :election_id";
        $candidatesStmt = $pdo->prepare($candidatesQuery);
        $candidatesStmt->execute(['election_id' => $selectedElectionId]);
        $candidates = $candidatesStmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($candidates)) {
            echo '<h2>Candidates</h2>';
            echo '<ul style="list-style-type:none;">';
            foreach ($candidates as $candidate) {
                echo '<li>';
                $imagePath = "candidate_photos/" . $candidate['photo'];
                if (file_exists($imagePath)) {
                    echo "<img class='party-photo d-flex' width='50' height='50' src='$imagePath' alt='Candidate Photo'>";
                } else {
                    echo "<p>Image not found</p>";
                }
                echo $candidate['name'] . ' - <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#voteModal' . $candidate['id'] . '">Vote</button>';
                echo '</li>';

                // Vote Modal Structure
                echo '
                <div class="modal fade" id="voteModal' . $candidate['id'] . '" tabindex="-1" role="dialog" aria-labelledby="voteModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="voteModalLabel">Vote for ' . $candidate['name'] . '</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Vote Form -->
                                <form action="submit_vote.php" method="post">
                                    <input type="hidden" name="election_id" value="' . $selectedElectionId . '">
                                    <input type="hidden" name="candidate_id" value="' . $candidate['id'] . '">
                                    <input type="submit" class="btn btn-primary" name="vote" value="Vote">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>';
            }
            echo '</ul>';
        } else {
            echo '<p>No candidates found for the selected election.</p>';
        }
    }

    // ... (Remaining code)

    ?>
    <div class="contain mt-4">
    <!-- Bootstrap Alert for displaying error messages -->
    <?php if (isset($_GET['error']) && !empty($_GET['error'])): ?>
        <?php
        // Retrieve the error message from the URL parameter
        $errorMessage = urldecode($_GET['error']);
        ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $errorMessage; ?>
        </div>
    <?php endif; ?>
</div>

</div>


</body>
</html>
