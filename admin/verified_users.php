<?php 


     // Include your database connection code here
include 'conn.php';
include 'header.php';
include 'functions.php';
include 'getPDO.php';


// Get the PDO instance
$pdo = getPDO(); // Replace this with your actual database connection setup

session_start();

// Check if the user is authenticated as an admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}


 ?>


<!DOCTYPE html>
<html lang="en">

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
                  <input type="text" class="form-control" placeholder="Search products">
                </form>
              </li>
            </ul>



            <ul class="navbar-nav navbar-nav-right">
              <li class="nav-item dropdown">
                <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
                  <div class="navbar-profile">
                    <img class="img-xs rounded-circle" src="assets/images/faces/face15.jpg" alt="">
                    <p class="mb-0 d-none d-sm-block navbar-profile-name">Welcome, <?php echo $_SESSION['admin_name']; ?></p>
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


<div class="container">
  <?php 
// Handle approval or rejection actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['voter_id'])) {
        $voterId = $_POST['voter_id'];

        // Initialize the $pdo variable
        $pdo = getPDO();

        if ($_POST['action'] === 'accredit') {
            // Perform accreditation logic here
            $updateQuery = "UPDATE voters SET status = 'accredited' WHERE voter_id = ?";
                 $updateQuery = "UPDATE users SET status = 'accredited' WHERE voter_id = ?";
            $stmt = $pdo->prepare($updateQuery);
            if ($stmt->execute([$voterId])) {
                // Send notifications for accreditation (if needed)
                echo "User accredited successfully.";
            } else {
                echo "Failed to accredit the user.";
            }
        }
    }
}


// Retrieve accredited users from the database
$selectQuery = "SELECT * FROM voters WHERE status = 'accredited'";
$selectQuery = "SELECT * FROM users WHERE status = 'accredited'";
$stmt = $pdo->query($selectQuery);
// Check if there are any users
if ($stmt->rowCount() > 0) {
    echo "<div class='container text-white' style='margin-top:100px;'>";
    echo "<table class='table table-bordered table-sm text-white'>";
    echo "<tr class='text-crimson'>";
    echo "<th scope='col'>Voter ID</th>";
    echo "<th scope='col'>Name</th>";
    echo "<th scope='col'>Email</th>";
    echo "<th scope='col'>Picture</th>";

    echo "</tr>";
    echo " </div>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr >";
        echo "<td>{$row['voter_id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>";
     // Display user profile picture if available
        if (isset($row['profile_picture'])) {
            $imagePath = "../profile_pictures/" . $row['profile_picture'];
            if (file_exists($imagePath)) {
                echo "<img class='user-photo d-flex' src='$imagePath' width='50' height='50' alt='Profile Picture'>";
            } else {
                echo "<p>No profile picture</p>";
            }
        } else {
            echo "<p>No profile picture</p>";
        }
        echo "</td>";
  

        echo "</tr>";

        // Modal for accreditation
        echo "<div class='modal fade' id='accreditModal{$row['voter_id']}' tabindex='-1' role='dialog' aria-labelledby='accreditModalLabel' aria-hidden='true'>";
        echo "<div class='modal-dialog' role='document'>";
        echo "<div class='modal-content'>";
        echo "<div class='modal-header'>";
        echo "<h5 class='modal-title' id='accreditModalLabel'>Accredit User</h5>";
        echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
        echo "<span aria-hidden='true'>&times;</span>";
        echo "</button>";
        echo "</div>";
        echo "<div class='modal-body'>";
        echo "<p>Are you sure you want to accredit this user?</p>";
        echo "</div>";
        echo "<div class='modal-footer'>";
        echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>No</button>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='action' value='accredit'>";
        echo "<input type='hidden' name='voter_id' value='{$row['voter_id']}'>";
        echo "<button type='submit' class='btn btn-outline-primary' id='accreditConfirmBtn{$row['voter_id']}'>Yes</button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }

    echo "</table>";
} else {
     echo "No accredited users found.";
}
 ?>
</div>

     
</div>
</div>
  <script>
    // JavaScript function to handle user accreditation
    function handleAccreditationConfirmation(voterId) {
        // Confirm accreditation
        var confirmation = confirm('Are you sure you want to toggle the accreditation status for this user?');

        if (confirmation) {
            // Implement the logic to toggle the accreditation status
            // For demonstration purposes, let's use AJAX to update the accreditation status

            // Change button text based on the current status
            var accreditButton = document.getElementById('accreditBtn' + voterId);
            if (accreditButton.innerHTML === 'Accredit') {
                accreditButton.innerHTML = 'Unaccredit';
            } else {
                accreditButton.innerHTML = 'Accredit';
            }

            // Simulate accreditation toggle (replace this with your actual logic)
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Successfully updated the accreditation status
                    alert('Accreditation status toggled for user with ID ' + voterId);
                }
            };
            
            // Send an AJAX request to update the accreditation status
            xhr.open('GET', 'update_accreditation.php?voterId=' + voterId, true);
            xhr.send();
        }
    }
</script>
              


                 <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
             
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    
  </body>
</html>