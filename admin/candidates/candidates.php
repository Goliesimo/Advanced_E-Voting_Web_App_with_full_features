<?php
include 'conn.php';

// Query to retrieve candidates from the database
$query = "SELECT * FROM candidates";
$stmt = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidates for Upcoming Election</title>

    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h1>Candidates for Upcoming Election</h1>
    <div class="container candidates-list">
        <?php
        // Check if there are candidates to display
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch()) {
                echo '<div class="candidate row justify-content-between">';
                echo '<img class="candidate-photo" width="100" height="100" src="../candidate_photos/' . $row['photo'] . '" alt="Candidate Photo">';
                echo '<img class="party-logo" width="100" height="100" src="../party_logos/' . $row['photo'] . '" alt="Candidate Photo">'; 
                 echo '<p style="font-weight:bold; font-size:30px;">' . $row['name'] . '</p>';
                echo '<p>Party: ' . $row['party'] . '</p>';
                echo '<p>Position: ' . $row['position'] . '</p>';
                echo '<p>Age: ' . $row['age'] . '</p>'; 
                echo '</div>';
            }
        } else {
            echo '<p>No candidates available for the upcoming election.</p>';
        }
        ?>
    </div>

    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
