<?php
// Include your database connection code here
include 'conn.php';
include 'getPDO.php';


// Get the PDO instance
$pdo = getPDO();

// Retrieve votes data from the database
$selectVotesQuery = "SELECT candidate_name, candidate_picture, election_name, COUNT(*) AS vote_count FROM votes GROUP BY candidate_name, candidate_picture, election_name";
$stmt = $pdo->query($selectVotesQuery);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Results</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Vote Results</h2>
    <?php 
// Check if there are any votes
if ($stmt->rowCount() > 0) {
    echo "<div class='container'>";
    echo "<table class='table table-striped table-bordered table-hover table-sm'>";
    echo "<tr>";

    echo "<th scope='col'>Candidate Name</th>";
    echo "<th scope='col'>Candidate Picture</th>";
    echo "<th scope='col'>Election Name</th>";
    echo "<th scope='col'>Vote Count</th>";
    echo "</tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['candidate_name']}</td>";
        echo "<td>";
        // Display candidate picture if available
        $candidatePicturePath = "candidate_photos/" . $row['candidate_picture'];
        if (file_exists($candidatePicturePath)) {
            echo "<img class='candidate-photo d-flex' src='$candidatePicturePath' alt='Candidate Picture' style='height:50px; width:50px; border-radius:50%' >";
        } else {
            echo "<p>No picture</p>";
        }
        echo "</td>";
        echo "<td>{$row['election_name']}</td>";
        echo "<td>{$row['vote_count']}</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";
} else {
    echo "No votes found.";
}

     ?>


     <!-- Include Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>


