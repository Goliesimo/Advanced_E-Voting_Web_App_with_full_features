<?php 

function updateCandidateDashboard($pdo, $candidateId, $notificationMessage) {
    // Update the candidate's dashboard in the database
    $updateQuery = "UPDATE candidates SET dashboard_notification = ? WHERE id = ?";
    $stmt = $pdo->prepare($updateQuery);
    
    if ($stmt->execute([$notificationMessage, $candidateId])) {
        // Successfully updated the dashboard_notification

        // Insert a notification into the notifications table
        $insertNotificationQuery = "INSERT INTO notifications (candidate_id, message) VALUES (?, ?)";
        $stmt = $pdo->prepare($insertNotificationQuery);
        
        if ($stmt->execute([$candidateId, $notificationMessage])) {
            // The notification has been added to the notifications table.
            
            // You can implement the actual notification system to display this message to the candidate.
            // This might involve AJAX to update the notification in real-time, or a simple check on the candidate_dashboard.php page to show notifications.
        } else {
            echo "Failed to insert the notification into the notifications table.";
        }
    } else {
        echo "Failed to update the candidate's dashboard.";
    }
}

function sendRejectionNotification($pdo, $applicationId) {
    // Implement your notification logic here for rejection
    $notificationMessage = "Your application (ID: $applicationId) has been rejected.";
    updateCandidateDashboard($pdo, $applicationId, $notificationMessage);
}

function sendApprovalNotification($pdo, $applicationId) {
    // Implement your notification logic here for approval
    $notificationMessage = "Your application (ID: $applicationId) has been approved.";
    updateCandidateDashboard($pdo, $applicationId, $notificationMessage);
}


 ?>