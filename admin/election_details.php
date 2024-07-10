<?php
// Include your database connection code
include 'conn.php';
include 'getPDO.php';

// Get the PDO instance
$pdo = getPDO(); // Replace this with your actual database connection setup

// Check if the user is authenticated
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit();
}

// Check if the election ID is provided in the URL
if (isset($_GET['id'])) {
    $electionId = $_GET['id'];

    // Query the database to get the election details
    $sql = "SELECT * FROM elections WHERE id = :electionId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':electionId', $electionId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $election = $stmt->fetch(PDO::FETCH_ASSOC);
?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Details</title>
<!-- Add Bootstrap CSS link -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Add Bootstrap JavaScript and jQuery links -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  
</head>

        <body>
            <div class="container">
                <h1><?php echo $election['name']; ?></h1>
                <table class="table">
                    <tr>
                        <th>Start Date</th>
                        <td><?php echo $election['start_date']; ?></td>
                    </tr>
                    <tr>
                        <th>End Date</th>
                        <td><?php echo $election['end_date']; ?></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><?php echo $election['description']; ?></td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td><?php echo $election['state']; ?></td>
                    </tr>
                </table>

                <!-- Add the link to view candidates -->
                <a href="#" data-toggle="modal" data-target="#candidatesModal">View Candidates for this position</a>

                <!-- The Modal -->
                <div class="modal fade" id="candidatesModal" tabindex="-1" role="dialog" aria-labelledby="candidatesModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="candidatesModalLabel">Candidates for this position</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <!-- Inside the modal-body div in election_details.php -->




// ... (Previous code)

    <div class="modal-body">
        <!-- Place the content of manage_candidates.php here -->
        <div class="container">
            <table class="table table-striped table-bordered table-hover table-sm">
                <!-- ... (Previous code) -->

                <tbody>
                    <?php
                    // ... (Previous code)

                    // Check if there are associated candidates
                    if (!empty($associatedCandidates)):
                        foreach ($associatedCandidates as $associatedCandidate):
                    ?>
                            <tr>
                                <td><?php echo $associatedCandidate['name']; ?></td>
                                <!-- ... (Previous code) -->

                                <td>
                                    <!-- Vote Button with Modal Form -->
                                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#voteModal<?php echo $associatedCandidate['id']; ?>">
                                        Vote
                                    </button>

                                    <!-- Vote Modal -->
                                    <div class="modal fade" id="voteModal<?php echo $associatedCandidate['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="voteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="voteModalLabel">Vote for <?php echo $associatedCandidate['name']; ?></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Vote Form -->
                                                    <form action="votes.php" method="post">
                                                        <input type="hidden" name="election_id" value="<?php echo $associatedCandidate['election_id']; ?>">
                                                        <input type="hidden" name="candidate_id" value="<?php echo $associatedCandidate['id']; ?>">
                                                        <input type="submit" class="btn btn-primary" name="vote" value="Vote">
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                    else:
                        echo "<tr><td colspan='3'>No associated candidates for this election.</td></tr>";
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ... (Remaining code) -->
?>







        <div class="modal-footer">
                <?php
       $voterId = $_SESSION['user_id']; // Adjust the key based on your actual implementation

// Check if the user has already voted in this election
$checkVoteQuery = "SELECT * FROM votes WHERE voter_id = :voter_id AND election_id = :election_id";
$checkVoteStmt = $pdo->prepare($checkVoteQuery);
$checkVoteStmt->execute(['voter_id' => $voterId, 'election_id' => $electionId]);

// Fetch the result
$existingVote = $checkVoteStmt->fetch(PDO::FETCH_ASSOC);

// Check if the user has already voted
if ($existingVote) {
    // Display a message or take appropriate action
    echo "You have already cast your vote for this election.";
} else {
    // Allow the user to cast their vote
    // Add the logic for casting the vote here
}
                ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
                        </div>
                    </div>
                </div>


              

        </body>
        </html>
<?php
    } else {
        echo "Election not found.";
    }
} else {
    echo "Election ID not provided.";
}
?>
