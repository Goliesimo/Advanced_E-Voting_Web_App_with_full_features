<?php
include 'conn.php'; // Include your database connection file
include 'getPDO.php'; // Include your getPDO.php file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $name = $_POST['name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $description = $_POST['description'];

    // Validate data (e.g., check that the start date is before the end date)
    if (strtotime($start_date) >= strtotime($end_date)) {
        echo "Start date must be before the end date.";
    } else {
        // Start date is before end date, so you can proceed to save the election details to the database.
        
        // Insert the election details into the 'elections' table
        $insertQuery = "INSERT INTO elections (name, start_date, end_date, description) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($insertQuery);
        
        if ($stmt->execute([$name, $start_date, $end_date, $description])) {
            echo "Election created successfully.";
            // Redirect to elections.php
     header("Location: elections.php?success=1");
    exit(); // Ensure no further code is executed
        } 
        else {
            echo "Failed to create the election.";
        }
    }
}
?>
