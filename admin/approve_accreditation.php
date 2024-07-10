<?php
// Include your database connection code here
include '../conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['voter_id'])) {
        $voterId = $_POST['voter_id'];

        // Check the current status before approving
        $selectQuery = "SELECT status FROM users WHERE voter_id = ?";
        $stmt = $pdo->prepare($selectQuery);

        if ($stmt->execute([$voterId])) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $currentStatus = $row['status'];

            // Toggle the status
            $newStatus = ($currentStatus === 'unaccredited') ? 'accredited' : 'unaccredited';

            // Update the status
            $updateQuery = "UPDATE users SET status = ? WHERE voter_id = ?";
            $stmt = $pdo->prepare($updateQuery);

            if ($stmt->execute([$newStatus, $voterId])) {
                echo "User status updated successfully.";
            } else {
                echo "Failed to update user status.";
            }
        } else {
            echo "Failed to retrieve user status.";
        }
    }
}
?>
