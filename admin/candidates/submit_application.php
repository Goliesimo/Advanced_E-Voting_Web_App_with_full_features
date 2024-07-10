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
        echo "You have already submitted an application for candidacy. You cannot apply twice. <br><a href='applicatiion_form.php'>Go back<a/> </br";
    } else {
        // Insert the data into the database (replace with your actual database table name)
        $insertQuery = "INSERT INTO candidate_applications (full_name, age, educational_status, party_affiliation, why_should_be_voted) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($insertQuery);

        if ($stmt->execute([$full_name, $age, $educational_status, $party_affiliation, $why_should_be_voted])) {
            echo "<h1>Application submitted successfully!</h1> <br> <h3>Admin will approve your application for candidacy, and you will receive a notification.</h3> <br> <a href='candidate_dashboard.php'>Return to your Dashboard</a>";
        } else {
            echo "Error submitting the application.";
        }
    }
} else {
    echo "Invalid request method.";
}
?>
