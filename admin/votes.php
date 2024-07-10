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

// Check if the vote form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vote'])) {
    // Retrieve values from the form
    $electionId = $_POST['election_id'];
    $candidateId = $_POST['candidate_id'];
    $voterId = $_SESSION['voter_id'];

    // Add your logic to insert the vote into the database
    $insertVoteQuery = "INSERT INTO votes (voter_id, election_id, candidate_id) VALUES (:voter_id, :election_id, :candidate_id)";
    $insertVoteStmt = $pdo->prepare($insertVoteQuery);
    $insertVoteStmt->execute(['voter_id' => $voterId, 'election_id' => $electionId, 'candidate_id' => $candidateId]);


    // Insert the vote into the votes table
    $insertVoteQuery = "INSERT INTO votes (voter_id, election_id, candidate_id) VALUES (:voter_id, :election_id, :candidate_id)";
    $insertVoteStmt = $pdo->prepare($insertVoteQuery);
    $insertVoteStmt->execute(['voter_id' => $voterId, 'election_id' => $electionId, 'candidate_id' => $candidateId]);

    // Check if the vote was successfully inserted
    if ($insertVoteStmt->rowCount() > 0) {
        $alertMessage = "Vote successfully casted!";
    } else {
        $alertMessage = "Failed to cast vote. Please try again.";
    }

    // Add a success message or redirect as needed
    $alertMessage = "Vote casted successfully!";
}

// Query the database to retrieve all existing elections
$selectQuery = "SELECT * FROM elections";
$stmt = $pdo->query($selectQuery);
$elections = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container mt-4">
        <!-- Bootstrap Alert (for displaying success messages after voting) -->
        <?php if (!empty($alertMessage)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $alertMessage; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="container">
        <h1>Vote in Elections</h1>
        <div class="row">
            <div class="col-3">
                <!-- Link to view available elections -->
                <a href="#" class="btn btn-outline-primary" data-toggle="modal" data-target="#viewElectionsModal">View Elections</a>
            </div>
            <div class="col-3">
                <!-- Link to view available elections -->
                <a href="candidates.php" class="btn btn-outline-primary">View Candidates</a>
            </div>
        </div>
    </div>

    <!-- Modal to display available elections -->
    <div class="modal fade" id="viewElectionsModal" tabindex="-1" role="dialog" aria-labelledby="viewElectionsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewElectionsModalLabel">Available Elections</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Display the list of elections with links to election details -->
                    <ul>
                        <?php foreach ($elections as $election): ?>
                            <li>
                                <a href="election_details.php?id=<?php echo $election['id']; ?>">
                                    <?php echo $election['name']; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function castVote(candidateId, electionId) {
        // You can implement the logic to submit the vote here
        // For example, you can use AJAX to send a request to a separate PHP script that handles the vote

        // For demonstration purposes, let's alert a success message
        alert('Vote casted successfully for candidate ID ' + candidateId + ' in election ID ' + electionId);

        // You can redirect the user to another page or perform any other necessary action
        window.location.href = 'vote.php';
    }
</script>


    <!-- Add Bootstrap JavaScript and jQuery links -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
