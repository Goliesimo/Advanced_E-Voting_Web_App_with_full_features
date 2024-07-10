<?php
// Include your database connection code here
include '../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['application_id'])) {
        $applicationId = $_POST['application_id'];

        // Check the current status before rejecting
        $selectQuery = "SELECT status FROM candidate_applications WHERE id = ?";
        $stmt = $pdo->prepare($selectQuery);

        if ($stmt->execute([$applicationId])) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $currentStatus = $row['status'];

            if ($currentStatus === 'pending') {
                // Update the status to "Rejected"
                $updateQuery = "UPDATE candidate_applications SET status = 'rejected' WHERE id = ?";
                $stmt = $pdo->prepare($updateQuery);

                if ($stmt->execute([$applicationId])) {
                    echo "<p class='text-white bg-dark'>Your Application for this position has been rejected. Contact Admin for more  help</p>";
                } else {
                    echo "Failed to reject the application.";
                }
            } else {
                echo "Application has already been approved or rejected.";
            }
        } else {
            echo "Failed to retrieve application status.";
        }
    }
}
