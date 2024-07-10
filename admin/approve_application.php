<?php
// Include your database connection code here
include '../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['application_id'])) {
        $applicationId = $_POST['application_id'];

        // Check the current status before approving
        $selectQuery = "SELECT status FROM candidate_applications WHERE id = ?";
        $stmt = $pdo->prepare($selectQuery);

        if ($stmt->execute([$applicationId])) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $currentStatus = $row['status'];

            if ($currentStatus === 'pending') {
                // Update the status to "Approved"
                $updateQuery = "UPDATE candidate_applications SET status = 'approved' WHERE id = ?";
                $stmt = $pdo->prepare($updateQuery);

                if ($stmt->execute([$applicationId])) {
                    echo "Application approved successfully.";
                } else {
                    echo "Failed to approve the application.";
                }
            } else {
                echo "Application has already been approved or rejected.";
            }
        } else {
            echo "Failed to retrieve application status.";
        }
    }
}
?>
