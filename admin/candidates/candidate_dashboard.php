<?php
session_start();

if (!isset($_SESSION['candidate_id'])) {
    header('Location: login.php');
    exit();
}

$candidate_id = $_SESSION['candidate_id'];
$candidate_username = $_SESSION['candidate_username'];
$profile_picture = $_SESSION['profile_picture'];

// Include your database connection code here
include 'conn.php';
include 'functions.php';
include 'getPDO.php';
$pdo = getPDO(); // You should replace this with your actual database connection setup


// Retrieve notifications for the current candidate from the database
$selectNotifications = "SELECT * FROM notifications WHERE candidate_id = ?";
$stmt = $pdo->prepare($selectNotifications);
if ($stmt->execute([$candidate_id])) {
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Failed to retrieve notifications.";
}

// Include the rest of your candidate_dashboard.php content here
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Candidate Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>

<div class="header">
    <div class="top-right">
        <?php
        $imagePath = 'profile_pictures/' . $_SESSION['profile_picture'];

        if (file_exists($imagePath)) {
            echo '<img src="' . $imagePath . '" alt="Candidate Profile Picture" width="100" height="100">';
        } else {
            echo 'Image not found.';
        }
        ?>
    </div>
</div>

<h2>Welcome, <?php echo $_SESSION['candidate_username']; ?></h2>
<div class="container">
   <p>Apply for Candidacy</p>
<a href="application_form.php" class="btn btn-outline-success">Apply Now</a>

    <h3>Notifications:</h3>
    <div class="row">
        <div class="col-3">
            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#applicationStatusModal">Application Status</button>
        </div>
        <div class="col-3">
        <a href="clear_notifications.php" class="btn btn-danger">Clear Notifications</a>
        </div>

</div>




<!-- Modal for Application Status -->
<div class="modal fade" id="applicationStatusModal" tabindex="-1" role="dialog" aria-labelledby="applicationStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applicationStatusModalLabel">Application Status Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Application status details will be displayed here -->
                <ul>
                    <?php
                    if (count($notifications) > 0) {
                        foreach ($notifications as $notification) {
                            echo '<li>' . $notification['message'] . '</li>';
                        }
                    } else {
                        echo '<li>No notifications.</li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Add links for other actions like log out or check application status -->
<p><a href="logout.php">Log Out</a></p>
 
</div>

</body>
</html>
