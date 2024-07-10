<?php
// Include your database connection code here
include 'conn.php';
include 'functions.php';
include 'getPDO.php';

session_start();

if (!isset($_SESSION['candidate_id'])) {
    header('Location: login.php');
    exit();
}

$candidate_id = $_SESSION['candidate_id'];
$candidate_username = $_SESSION['candidate_username'];

// Get the PDO instance
$pdo = getPDO(); // Replace this with your actual database connection setup

// Retrieve candidate information from the database
$query = "SELECT * FROM candidates_register WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$candidate_id]);
$candidate = $stmt->fetch();

// Retrieve notifications for the current candidate from the database
$selectNotifications = "SELECT * FROM notifications WHERE candidate_id = ?";
$stmt = $pdo->prepare($selectNotifications);
if ($stmt->execute([$candidate_id])) {
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Failed to retrieve notifications.";
    exit();
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
  
</div>
<div class="d-flex justify-content-center mt-4 mb-4">
    <div class="top-left">
        <?php
        // Display the candidate profile picture if available
        if ($candidate && isset($candidate['profile_picture'])) {
          
              $imagePath = "profile_pictures/" . $candidate['profile_picture'];

            if (file_exists($imagePath)) {
                echo '<img src="' . $imagePath . '" alt="Candidate Profile Picture" width="50" height="50" style="border-radius: 50%;">';
            } else {
                echo '<p>No profile picture - File does not exist at path: ' . $imagePath . '</p>';
            }
        } else {
            echo '<p>No profile picture - Candidate or profile picture not set</p>';
        }
        ?>
    </div> 
  <h2>Welcome, <?php echo $_SESSION['candidate_username']; ?></h2> 
    
</div>


<div class="container">
    <div class="row">
        <div class="col-4">
               <h3>Apply for Candidacy</h3>
<a href="application_form.php" class="btn btn-outline-success">Apply Now</a>
        </div>

        <div class="col-4">
              <h3>Notifications</h3>
            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#applicationStatusModal">Application Status</button>
        </div>

         </div> <br>


         <div class="row">
             <div class="col-4">
                   <a href="clear_notifications.php" class="btn btn-danger">Clear Notifications</a>
             </div>

                <div class="col-4">
             <!-- Add links for other actions like log out or check application status -->
<p><a href="logout.php">Log Out</a></p>
         </div>
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
                            echo '<h5>' . $notification['message'] . '</h5>';
                        }
                    } else {
                        echo '<h5>No notifications.</h5>';
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



 
</div>

</body>
</html>
