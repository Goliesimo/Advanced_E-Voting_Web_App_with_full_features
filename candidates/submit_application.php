<?php
// Include your database connection code here
include '../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $educational_status = $_POST['educational_status'];
    $party_affiliation = $_POST['party_affiliation'];
    $why_should_be_voted = $_POST['why_should_be_voted'];

    // Add data validation and sanitization here

    // Check if the candidate has already submitted an application
    $checkQuery = "SELECT id FROM candidate_applications WHERE full_name = ?";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->execute([$full_name]);

    if ($checkStmt->fetch()) {
        echo "<h1>You have already submitted an application for candidacy</h1>. <br> <h6>You cannot apply twice.</h6> <br><a href='candidate_dashboard.php' class='btn btn-primary'>Go back<a/> </br";
    } else {
        // Insert the data into the database (replace with your actual database table name)
        $insertQuery = "INSERT INTO candidate_applications (full_name, age, educational_status, party_affiliation, why_should_be_voted) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($insertQuery);

        if ($stmt->execute([$full_name, $age, $educational_status, $party_affiliation, $why_should_be_voted])) {
            echo "<h1>Application submitted successfully!</h1> <br> <h3>Admin will approve your application for candidacy, and you will receive a notification.</h3> <br> <a href='candidate_dashboard.php' class='btn btn-primary'>Return to your Dashboard</a>";
        } else {
            echo "Error submitting the application.";
        }
    }
} else {
    echo "Invalid request method.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Application Form</title>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>



</body>
</html>
