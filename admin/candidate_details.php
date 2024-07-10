
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Details</title>
    <link rel="stylesheet" href="../styles.css">
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Candidate Details</h1>
        <div class="col-4">
            <a href="candidates.php" class="btn btn-outline-primary">Go back</a>
        </div>


        <?php
include '../conn.php'; // Adjust the path to your database connection file
include 'getPDO.php';

// Check if the candidate ID is provided in the URL
if (isset($_GET['id'])) {
    $candidateId = $_GET['id'];

    // Query to retrieve candidate details from the database based on ID
    $query = "SELECT * FROM candidates WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $candidateId, PDO::PARAM_INT);
    $stmt->execute();

    // Check if the candidate details are available
    if ($stmt->rowCount() > 0) {
        // Fetch the candidate details as an associative array
        $candidateDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        // Output candidate details in HTML format
        echo '<div class="container col-md-8">';
        echo '<div class="row">';
        echo '<div class="d-flex">';
        echo '<img class="party-photo mr-3 mb-4" width="100" height="100" src="../candidate_photos/' . $candidateDetails['photo'] . '" alt="Candidate Photo">';
        echo '<img class="candidate-photo mr-3 mb-4" width="50" height="50" src="../party_logos/' . $candidateDetails['photo'] . '" alt="Candidate Photo">';
        echo '<div class="" style="font-size:20px; font-weight:bold">';
        echo '<p style="font-weight:bold; font-size:30px;">' . $candidateDetails['name'] . '</p>';
        echo '<p>Party: ' . $candidateDetails['party'] . '</p>';
        echo '<p>Position: ' . $candidateDetails['position'] . '</p>';
        echo '<p>Age: ' . $candidateDetails['age'] . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } else {
        // No candidate details available
        echo '<p>No candidate details available</p>';
    }
} else {
    // Candidate ID not provided in the URL
    echo '<p>Candidate ID not provided</p>';
}
?>



    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
