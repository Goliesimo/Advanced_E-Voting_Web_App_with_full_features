<?php
// Include your database connection code (conn.php)
include 'conn.php';
include 'getPDO.php';

// Check if the user is authenticated as an admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $electionId = $_POST['electionId'];
    $electionName = $_POST['electionName'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $description = $_POST['description'];
   

    // Validate form data (you can add more validation rules as needed)
    if (empty($electionName) || empty($startDate) || empty($endDate) || empty($description)) {
        // Handle validation error, you can redirect back to the edit page with an error message
        header('Location: edit_election.php?id=' . $electionId . '&error=1');
        exit();
    }

    // Update the election details in the database
    $sql = "UPDATE elections SET 
            name = :electionName,
            start_date = :startDate,
            end_date = :endDate,
            description = :description,
           
            WHERE id = :electionId";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':electionId', $electionId, PDO::PARAM_INT);
    $stmt->bindParam(':electionName', $electionName, PDO::PARAM_STR);
    $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
    $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);


    // Execute the statement
    if ($stmt->execute()) {
        // Redirect back to the edit page with a success message
        header('Location: edit_election.php?id=' . $electionId . '&success=1');
        exit();
    } else {
        // Handle database error, redirect back to the edit page with an error message
        header('Location: edit_election.php?id=' . $electionId . '&error=2');
        exit();
    }
} else {
    // If the form is not submitted, redirect to the homepage or handle as needed
    header('Location: index.php');
    exit();
}
?>

<script>
    function validateForm() {
    console.log('Validating form...'); // Log a message to indicate that the validation is occurring

    var electionName = document.getElementById("electionName").value;
    var startDate = document.getElementById("start_date").value;
    var endDate = document.getElementById("end_date").value;
    var description = document.getElementById("description").value;
   

    if (electionName === "") {
        alert("Election Name must be filled out");
        return false;
    }

    if (startDate === "") {
        alert("Start Date must be filled out");
        return false;
    }

    if (endDate === "") {
        alert("End Date must be filled out");
        return false;
    }

    // Additional validation for Start Date and End Date
    var startDateObj = new Date(startDate);
    var endDateObj = new Date(endDate);
    var currentDate = new Date();

    if (startDateObj > endDateObj) {
        alert("Start Date cannot be later than End Date");
        return false;
    }

    if (startDateObj < currentDate) {
        alert("Start Date must be in the future");
        return false;
    }

    // You can add more validation rules for other fields as needed

    console.log('Validation passed!'); // Log a message to indicate that the validation passed
    return true; // If all validation passes, the form will be submitted
}

</script>