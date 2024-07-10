<?php
// Include your database connection code here
include 'conn.php';
include 'getPDO.php';
include 'header.php'; // Adjust the path if needed

// Get the PDO instance
$pdo = getPDO();

// Retrieve votes data from the database
$selectVotesQuery = "SELECT * FROM votes";
$stmt = $pdo->query($selectVotesQuery);

// Check if there are any votes
if ($stmt->rowCount() > 0) {
    echo "<div class='container'>";
    echo "<table class='table table-striped table-bordered table-hover table-sm'>";
    echo "<tr>";
  
    echo "<th scope='col'>Voter ID</th>";


    echo "<th scope='col'>Candidate Name</th>";
    echo "<th scope='col'>Candidate Picture</th>";
    echo "<th scope='col'>Election Name</th>";
    echo "</tr>";
    echo "</div>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
   
        echo "<td>{$row['voter_id']}</td>";
       
     
        echo "<td>{$row['candidate_name']}</td>";
        echo "<td>";
        // Display candidate picture if available
        $candidatePicturePath = "../candidate_photos/" . $row['candidate_picture'];
        if (file_exists($candidatePicturePath)) {
            echo "<img class='candidate-photo d-flex' src='$candidatePicturePath' width='30' height='30' alt='Candidate Picture' style='height:50px; width:50px' >";
        } else {
            echo "<p>No picture</p>";
        }
        echo "</td>";
        echo "<td>{$row['election_name']}</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No votes found.";
}
?>
