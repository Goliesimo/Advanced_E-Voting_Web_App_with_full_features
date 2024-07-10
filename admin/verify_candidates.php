<?php 
// Include your database connection code here
include 'conn.php';
include 'header.php';
include 'functions.php';
include 'getPDO.php';

session_start();

// Check if the user is authenticated as an admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];
$query = "SELECT profile_picture FROM users WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$admin_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<title>Verify Candidates</title>

  <body>
    <div class="container-scroller">
      <div class="row p-0 m-0 proBanner" id="proBanner">
       
      </div>
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
          <a class="sidebar-brand brand-logo" href="dashboard.php">E-Voting</a>
          <a class="sidebar-brand brand-logo-mini" href="dashboard.php">E-Voting</a>
        </div>
        <ul class="nav">
          <li class="nav-item profile">
          </li>
          <li class="nav-item nav-category">
            <span class="nav-link">Admin Functions</span>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="manage_users.php">
              <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
              </span>
              <span class="menu-title">Manage Users <a class="" href="manage_users.php"></a></span>

            </a>
          </li>
      
          <li class="nav-item menu-items">
            <a class="nav-link" href="verify_candidates.php">
              <span class="menu-icon">
                <i class="mdi mdi-playlist-play"></i>
              </span>
           <span class="menu-title">Verify Candidates <a class="" href="verify_candidates.php"></a></span>
            </a>
          </li>


          <li class="nav-item menu-items">
            <a class="nav-link" href="candidates.php">
              <span class="menu-icon">
                <i class="mdi mdi-table-large"></i>
              </span>
           <span class="menu-title">Manage Candidates <a class="" href="candidates.php"></a></span>
            </a>
          </li>


          <li class="nav-item menu-items">
            <a class="nav-link" href="elections.php">
              <span class="menu-icon">
                <i class="mdi mdi-chart-bar"></i>
              </span>
         <span class="menu-title">Create Election <a class="" href="elections.php"></a></span>
            </a>
          </li>


          <li class="nav-item menu-items">
            <a class="nav-link" href="manage_elections.php">
              <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
              </span>
             <span class="menu-title">Manage Elections <a class="" href="manage_elections.php"></a></span>
            </a>
          </li>

           <li class="nav-item menu-items">
            <a class="nav-link" href="accredit_users.php">
              <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
              </span>
             <span class="menu-title">Accredit Voters<a class="" href="accredit_users.php"></a></span>
            </a>
          </li>

           <li class="nav-item menu-items">
            <a class="nav-link" href="verified_users.php">
              <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
              </span>
             <span class="menu-title">Accredited Voters List<a class="" href="verified_users.php"></a></span>
            </a>
          </li>
        </ul>
      </nav>


      

      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar p-0 fixed-top d-flex flex-row">
          <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="index.php"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
          </div>
          <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="mdi mdi-menu"></span>
            </button>
            <ul class="navbar-nav w-100">
              <li class="nav-item w-100">
                <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
                  <input type="text" class="form-control" placeholder="Search something...">
                </form>
              </li>
            </ul>



            
            <ul class="navbar-nav navbar-nav-right">
              <li class="nav-item dropdown">
                <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
                  <div class="navbar-profile">
                  <div class="col-4">
            <div class="user-profile-picture">
    <?php
    // Display the profile picture if available
    if ($user && isset($user['profile_picture'])) {
        $imagePath = "profile_pictures/" . $user['profile_picture'];

        if (file_exists($imagePath)) {
            echo "<img class='user-photo p-0 m-0' width='50' height='50' style='border-radius:50%; padding:0px; margin:0px;' src='$imagePath' alt='Profile Picture'>";
        } else {
            echo "<p>No profile picture - File does not exist at path: $imagePath</p>";
        }
    } else {
        echo "<p>No profile picture - User or profile picture not set</p>";
    }
    ?>
</div>
        </div>
                    <p class="m-0 p-0 d-none d-sm-block navbar-profile-name">Welcome, <?php echo $_SESSION['admin_name']; ?></p>
                    <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
              
                  <div class="dropdown-divider"></div>
                 
                  <div class="dropdown-divider"></div>
                
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-dark rounded-circle">
                       <a href="logout.php"><i class="mdi mdi-logout text-danger"></i>Logout</a>
                      </div>
                    </div>
              </li>
            </ul>




            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="mdi mdi-format-line-spacing"></span>
            </button>
          </div>
        </nav>
<body>

    <?php
// Handle approval, rejection, or deletion actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['application_id'])) {
        $applicationId = $_POST['application_id'];

        // Initialize the $pdo variable
        $pdo = getPDO();

        if ($_POST['action'] === 'approve') {
            // Perform approval logic here
            $updateQuery = "UPDATE candidate_applications SET status = 'Approved' WHERE id = ?";
            $stmt = $pdo->prepare($updateQuery);
            if ($stmt->execute([$applicationId])) {
                // Send notifications for approval
                sendApprovalNotification($pdo, $applicationId);
                echo "<p class='text-center text-black bg-white' style='margin-top:100px'>Your Application has been approved successfully. You are now eligible as a candidate in the ongoing elections</p>";
            } else {
                echo "<p class='text-center text-black bg-white' style='margin-top:100px'>Application rejected successfully.</p>";
            }
        } elseif ($_POST['action'] === 'reject') {
            // Perform rejection logic here
            $updateQuery = "UPDATE candidate_applications SET status = 'Rejected' WHERE id = ?";
            $stmt = $pdo->prepare($updateQuery);
            if ($stmt->execute([$applicationId])) {
                // Send notifications for rejection
                sendRejectionNotification($pdo, $applicationId);
                echo "<p class='text-center text-black bg-white' style='margin-top:100px'>Your Application has been rejected, Please Apply again or contact Admin for help!</p>";
            } else {
                echo "Failed to reject the application.";
            }
        } elseif ($_POST['action'] === 'delete') {
            // Perform deletion logic here
            $deleteQuery = "DELETE FROM candidate_applications WHERE id = ?";
            $stmt = $pdo->prepare($deleteQuery);
            if ($stmt->execute([$applicationId])) {
                echo "<script>window.location.href = 'verify_candidates.php';</script>";
                exit();
            } else {
                echo "<p class='text-center text-black bg-white' style='margin-top:100px'>Failed to delete the candidate.</p>";
            }
        }
    }
}













// Retrieve candidate applications from the database
$selectQuery = "SELECT * FROM candidate_applications";
$stmt = $pdo->query($selectQuery);

// Check if there are any applications
if ($stmt->rowCount() > 0) {
    echo "<div class='container' style='margin-top:100px'>";
    echo "<table class='table table-bordered table-sm text-white'>";
    echo "<tr>";
    echo "<th scope='col'>Full Name</th>";
    echo "<th scope='col'>Age</th>";
    echo "<th scope='col'>Matric No</th>";
    echo "<th scope='col'>Department</th>";
    echo "<th scope='col'>Position</th>";
    echo "<th scope='col'>Action</th>";
    echo "</tr>";
    echo " </div>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr >";
        echo "<td>{$row['full_name']}</td>";
        echo "<td>{$row['age']}</td>";
        echo "<td>{$row['educational_status']}</td>";
        echo "<td>{$row['party_affiliation']}</td>";
        echo "<td>{$row['why_should_be_voted']}</td>";


        echo "<td>";
    echo "<button type='button' class='badge badge-outline-success' class='btn btn-primary' data-toggle='modal' data-target='#approveModal{$row['id']}' id='approveBtn{$row['id']}' onclick='handleConfirmation({$row['id']}, \"approve\")'>Approve</button>";

echo "<button type='button' class='badge badge-outline-danger' data-toggle='modal' data-target='#rejectModal{$row['id']}' id='rejectBtn{$row['id']}' onclick='handleConfirmation({$row['id']}, \"reject\")'>Reject</button>";

echo "<button type='button' class='badge badge-danger' data-toggle='modal' data-target='#deleteModal{$row['id']}' id='deleteBtn{$row['id']}' onclick='handleConfirmation({$row['id']}, \"delete\")'>Delete</button>";



        echo "</td>";
        echo "</tr>";


   // Modals for delete
        echo "<div class='modal fade' id='deleteModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel' aria-hidden='true'>";
echo "<div class='modal-dialog' role='document'>";
echo "<div class='modal-content'>";
echo "<div class='modal-header'>";
echo "<h5 class='modal-title' id='deleteModalLabel'>Delete Candidate</h5>";
echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
echo "<span aria-hidden='true'>&times;</span>";
echo "</button>";
echo "</div>";
echo "<div class='modal-body'>";
echo "<p>Are you sure you want to delete this candidate?</p>";
echo "</div>";
echo "<div class='modal-footer'>";
echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>No</button>";
echo "<form method='post'>";
echo "<input type='hidden' name='action' value='delete'>";
echo "<input type='hidden' name='application_id' value='{$row['id']}'>";
echo "<button type='submit' class='btn btn-danger' id='deleteConfirmBtn{$row['id']}'>Yes</button>";
echo "</form>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";


        // Modals for approval and rejection
        echo "<div class='modal fade' id='approveModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='approveModalLabel' aria-hidden='true'>";
        echo "<div class='modal-dialog' role='document'>";
        echo "<div class='modal-content'>";
        echo "<div class='modal-header'>";
        echo "<h5 class='modal-title' id='approveModalLabel'>Approve Application</h5>";
        echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
        echo "<span aria-hidden='true'>&times;</span>";
        echo "</button>";
        echo "</div>";
        echo "<div class='modal-body'>";
        echo "<p>Are you sure you want to approve this application?</p>";
        echo "</div>";
        echo "<div class='modal-footer'>";
        echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>No</button>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='action' value='approve'>";
        echo "<input type='hidden' name='application_id' value='{$row['id']}'>";
        echo "<button type='submit' class='btn btn-primary' id='approveConfirmBtn{$row['id']}'>Yes</button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";

  // Modals for approval and rejection
        echo "<div class='modal fade' id='rejectModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='rejectModalLabel' aria-hidden='true'>";
        echo "<div class='modal-dialog' role='document'>";
        echo "<div class='modal-content'>";
        echo "<div class='modal-header'>";
        echo "<h5 class='modal-title' id='rejectModalLabel'>Reject Application</h5>";
        echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
        echo "<span aria-hidden='true'>&times;</span>";
        echo "</button>";
        echo "</div>";
        echo "<div class='modal-body'>";
        echo "<p>Are you sure you want to reject this application?</p>";
        echo "</div>";
        echo "<div class='modal-footer'>";
        echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>No</button>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='action' value='reject'>";
        echo "<input type='hidden' name='application_id' value='{$row['id']}'>";
        echo "<button type='submit' class='btn btn-danger' id='rejectConfirmBtn{$row['id']}'>Yes</button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }

    echo "</table>";
} else {
    echo "No candidate applications found.";
}
?>
<script>
  // Function to disable the buttons
  function disableButtons(buttonId) {
    document.getElementById(buttonId).disabled = true;
  }

  // Attach the function to your modal's confirm buttons
  document.getElementById('approveYes').addEventListener('click', function() {
    disableButtons('approveBtn');
  });

  document.getElementById('rejectYes').addEventListener('click', function() {
    disableButtons('rejectBtn');
  });
</script>


<script>
    // Function to disable buttons and handle approval/rejection
    function handleConfirmation(applicationId, action) {
        // Display the confirmation modal
        var confirmationModal = document.getElementById(action + 'Modal' + applicationId);
        $(confirmationModal).modal('show');

        // Add event listener for the "Yes" button
        var confirmButton = document.getElementById(action + 'Confirm' + applicationId);
        confirmButton.addEventListener('click', function () {
            // Disable the button
            var button = document.getElementById(action + 'Btn' + applicationId);
            button.disabled = true;
            
            // Perform the approval or rejection logic
            // You can send an AJAX request to the server here if needed

            // Close the confirmation modal
            $(confirmationModal).modal('hide');
            
            // Show a success message
            var successMessage = document.getElementById(action + 'SuccessMessage' + applicationId);
            successMessage.style.display = 'block';
        });
    }
</script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    
  </body>
</html>

